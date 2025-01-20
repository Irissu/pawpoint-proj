<?php
namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;


class Register extends BaseRegister
{
    public function form(Form $form): Form
    {
        return $form
        ->schema([
            $this->getNameFormComponent(),
            TextInput::make('surname')
            ->label('Apellidos')
            ->required(),
            $this->getEmailFormComponent(),
            $this->getPasswordFormComponent()
            ->validationMessages([
                'same' => 'Las contraseñas no coinciden',
                'min' => 'La contraseña debe tener al menos 8 caracteres',
                'max' => 'La contraseña no puede tener más de 255 caracteres',
            ]),
            $this->getPasswordConfirmationFormComponent(),
            TextInput::make('phone')
            ->label('Teléfono')
            ->numeric()
            ->length(9)
            ->required(),
            TextInput::make('address')
            ->label('Dirección'),
            Hidden::make('role')
            ->default('owner'),
        ]);
    }
}

?>