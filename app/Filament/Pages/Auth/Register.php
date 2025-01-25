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
            $this->getNameFormComponent()
            ->minLength(2),
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
            ->validationMessages([
                'required' => 'El teléfono es obligatorio',
                'digits' => 'El teléfono debe tener 9 dígitos',
                'numeric' => 'El teléfono debe ser un número',
                'length' => 'El teléfono debe tener 9 dígitos',
            ])
            ->required(),
            TextInput::make('address')
            ->label('Dirección'),
            Hidden::make('role')
            ->default('owner'),
        ]);
    }
}

?>