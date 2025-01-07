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
            $this->getPasswordFormComponent(),
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