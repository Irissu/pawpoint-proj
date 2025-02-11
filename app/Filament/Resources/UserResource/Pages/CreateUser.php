<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Auth\VerifyEmail;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    
    protected function afterCreate(): void
    {
        $user = $this->getRecord();

        // Si el usuario necesita verificar el email, le enviamos la notificación
        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            $notification = new VerifyEmail();
            $notification->url = Filament::getVerifyEmailUrl($user);
            $user->notify($notification);
        }
    }

    protected function getRedirectUrl(): string // This method is used to redirect the user to the index page after editing a record
    {
        return $this->getResource()::getUrl('index');
    }
}
