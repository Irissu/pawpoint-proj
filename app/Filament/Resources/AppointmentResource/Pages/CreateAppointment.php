<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use App\Enums\RoleUsers;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array {
        // Asignar automáticamente el ID del usuario logueado si es un dueño
        if (Auth::user()->role === RoleUsers::User) {
            $data['owner_id'] = Auth::id();
        }

        if(isset($data['pet_name'])){
            $pet = \App\Models\Pet::find($data['pet_name']);
            if($pet){
                $data['pet_name'] = $pet->name;
                $data['pet_type'] = $pet->type;
            }
        }
    
        // Buscar y asignar el end_time correspondiente al start_time seleccionado
        if (isset($data['start_time'])) {
            $slot = \App\Models\Slot::find($data['start_time']);
            if ($slot) {
                $data['start_time'] = $slot->start_time;
                $data['end_time'] = $slot->end_time;
            }
        }
    
        return $data;
    }

    protected function getRedirectUrl(): string // This method is used to redirect the user to the index page after editing a record
    {
        return $this->getResource()::getUrl('index');
    }
    
}
