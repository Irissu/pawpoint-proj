<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Enums\DaysOfTheWeek;
use App\Filament\Resources\ScheduleResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Notification;
use App\Models\Slot;
use Illuminate\Support\Facades\Log;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (Auth::user()->isVet()) {
            $data['vet_id'] = Auth::id();
        }
    
        // Comprobar si hay un horario que se solape
        $overlappingSchedule = \App\Models\Schedule::where('vet_id', $data['vet_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                      ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                      ->orWhere(function ($query) use ($data) {
                          $query->where('start_time', '<', $data['start_time'])
                                ->where('end_time', '>', $data['end_time']);
                      });
            })
            ->exists();
    
        if ($overlappingSchedule) {
            \Filament\Notifications\Notification::make()
                ->title('Error al crear el horario')
                ->body('Ya existe un horario que se solapa con el que intentas crear.')
                ->danger()
                ->send();
            
            $this->halt();
        }
    
        return $data;
    }
    
    protected function beforeCreate(): void
    {
        if (!Auth::user()->isVet() && !Auth::user()->isAdmin()) {
            Notification::make()
                ->title('Acceso denegado')
                ->body('No tienes permisos para acceder a esta página')
                ->warning()
                ->send();
            $this->halt();
            $this->redirect('/');
        }
    }

    protected function afterCreate(): void
    {
        if(!$this->record->is_active) {
            return;
        }

        Carbon::setLocale('es');
       
        $schedule = $this->record; 
        // Día y hora actuales
        $currentDay = now()->dayOfWeek; 
        $currentTime = now();
        // Rango de tiempo del horario
        $startTime = Carbon::parse($schedule->start_time); // recoge correctamente la hora que le paso
        $endTime = Carbon::parse($schedule->end_time); // recoge correctamente la hora que le paso
    
        Log::info('Current Day: ' . $currentDay); // me devuelve 4
        Log::info('Current Time: ' . $currentTime);
        Log::info('Start Time: ' . $startTime);
        Log::info('End Time: ' . $endTime);
        Log::info('Schedule Day of Week: ' . $schedule->day_of_week); 
    
        // Si el día actual coincide con el día del horario
        if ($currentDay == $schedule->day_of_week) {
            Log::info('Current day matches schedule day.');
    
            if ($currentTime->between($startTime, $endTime)) {
                // Ajustar a la próxima hora válida dentro del rango
                $adjustedStart = $currentTime->copy()->addMinutes(30 - ($currentTime->minute % 30))->startOfMinute();
                if ($adjustedStart < $endTime) {
                    $startTime = $adjustedStart;
                    Log::info('Adjusted Start Time: ' . $startTime);
                } else {
                    // Si el ajuste excede el rango, no se crean slots hoy
                    Log::info('No slots available today; adjusted time exceeds end time.');
                    return;
                }
            } elseif ($currentTime->gt($endTime)) {
                // Si la hora actual es posterior al rango, pasa al próximo día correspondiente
                $nextScheduleDay = now()->next(DaysOfTheWeek::from($schedule->day_of_week)->name);
                $startTime = Carbon::parse($schedule->start_time);
                Log::info('Next Schedule Day: ' . $nextScheduleDay);
            }
        } else {
            // Si no coincide el día, pasa al próximo día correspondiente 
            $nextScheduleDay = now()->next(DaysOfTheWeek::from($schedule->day_of_week)->name);
            $startTime = Carbon::parse($schedule->start_time); 
        }
    
        // Crea los slots en el día correcto
        $date = isset($nextScheduleDay) ? $nextScheduleDay->toDateString() : now()->toDateString();
        while ($startTime < $endTime) {
            Slot::create([
                'vet_id' => $schedule->vet_id,
                'date' => $date,
                'start_time' => $startTime->toTimeString(),
                'end_time' => $startTime->copy()->addMinutes(30)->toTimeString(),
                'status' => 'available',
                'schedule_id' => $schedule->id,
            ]);
    
            // Avanzar al siguiente slot
            $startTime->addMinutes(30);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
