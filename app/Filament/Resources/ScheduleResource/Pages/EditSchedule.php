<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;
use App\Models\Slot;

class EditSchedule extends EditRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $schedule = $this->record;
    
        // Estado original de is_active 
        $originalIsActive = $schedule->is_active;
        $newIsActive = $data['is_active'] ?? $originalIsActive;
    
        // Estado original y nuevo de los horarios
        $originalStartTime = $schedule->start_time;
        $originalEndTime = $schedule->end_time;
        $originalDayOfWeek = $schedule->day_of_week;
    
        $newStartTime = $data['start_time'] ?? $originalStartTime;
        $newEndTime = $data['end_time'] ?? $originalEndTime;
        $newDayOfWeek = $data['day_of_week'] ?? $originalDayOfWeek;
    
        Log::info("Original is_active: $originalIsActive, New is_active: $newIsActive");
        Log::info("Original Schedule: $originalDayOfWeek, $originalStartTime - $originalEndTime");
        Log::info("New Schedule: $newDayOfWeek, $newStartTime - $newEndTime");
    
        // Caso 1: Desactivar horario
        if (!$newIsActive) {
            Log::info('El horario se ha desactivado. Eliminando slots...');
            $this->deleteSlots($schedule);
        } else {
            // Caso 2: Detecta cambios en el horario si el horario está activo
            if (
                $originalStartTime != $newStartTime ||
                $originalEndTime != $newEndTime ||
                $originalDayOfWeek != $newDayOfWeek
            ) {
                Log::info('El horario ha cambiado. Actualizando slots...');
                $this->updateSlots($schedule, $newStartTime, $newEndTime, $newDayOfWeek);
            }
        }
    
        return $data;
    }
    
    private function updateSlots($schedule, $newStartTime, $newEndTime, $newDayOfWeek): void
    {
        // Elimina los slots existentes
        $this->deleteSlots($schedule);
    
        // Crea nuevos slots con el nuevo rango
        $this->createSlots($schedule, $newStartTime, $newEndTime, $newDayOfWeek);
    }
    
    private function createSlots($schedule, $startTime, $endTime, $dayOfWeek): void
    {
        $startTime = \Carbon\Carbon::parse($startTime);
        $endTime = \Carbon\Carbon::parse($endTime);
        $date = now()->next(\App\Enums\DaysOfTheWeek::from($dayOfWeek)->name)->toDateString();
    
        while ($startTime < $endTime) {
            Slot::create([
                'vet_id' => $schedule->vet_id,
                'date' => $date,
                'start_time' => $startTime->toTimeString(),
                'end_time' => $startTime->copy()->addMinutes(30)->toTimeString(),
                'status' => 'available',
                'schedule_id' => $schedule->id,
            ]);
    
            $startTime->addMinutes(30);
        }
    }
    
    private function deleteSlots($schedule): void
    {
        Slot::where('schedule_id', $schedule->id)->delete();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

        protected function beforeSave(): void
    {
        $schedule = $this->record; 

        $vetId = $schedule->vet_id;
        $dayOfWeek = $this->data['day_of_week'];
        $startTime = $this->data['start_time'];
        $endTime = $this->data['end_time'];

        
        $overlappingSchedule = \App\Models\Schedule::where('vet_id', $vetId)
            ->where('day_of_week', $dayOfWeek)
            ->where('id', '!=', $schedule->id) 
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($query) use ($startTime, $endTime) {
                        $query->where('start_time', '<', $startTime)
                                ->where('end_time', '>', $endTime);
                    });
            })
            ->exists();

        if ($overlappingSchedule) {
            \Filament\Notifications\Notification::make()
                ->title('Error al actualizar el horario')
                ->body('El horario editado se solapa con otro horario existente.')
                ->danger()
                ->send();

            $this->halt(); 
        }
    }





}
