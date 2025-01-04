<?php

namespace App\Filament\Resources\PetResource\Pages;

use App\Filament\Resources\PetResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePet extends CreateRecord
{
    protected static string $resource = PetResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Verifica si el usuario autenticado es un "Owner"
        if (Auth::user()->isOwner()) {
            // Asigna el ID del usuario al campo 'owner_id'
            $data['owner_id'] = Auth::id();
        }

        return $data;
    }

    protected function getRedirectUrl(): string // This method is used to redirect the user to the index page after editing a record
    {
        return $this->getResource()::getUrl('index');
    }
}
