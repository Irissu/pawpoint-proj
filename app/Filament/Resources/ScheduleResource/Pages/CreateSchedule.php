<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Notification;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if(Auth::user()->isVet()) {
            $data['vet_id'] = Auth::id();
        }
        return $data;
    }

    protected function beforeCreate(): void
        {
            if(!Auth::user()->isVet() && !Auth::user()->isAdmin()) {
               Notification::make()
                ->title('Acceso denegado')
                ->body('No tienes permisos para acceder a esta página')
                ->warning()
                ->send();
                $this->halt();
                $this->redirect('/');
            }
        }
    }