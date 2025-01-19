<?php
 
namespace App\Filament\Pages\Auth;
 
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
 
class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('img_path')
                    ->label('Avatar')
                    ->image(),
/*                 TextInput::make('username')
                    ->required()
                    ->maxLength(255), */
                $this->getNameFormComponent(),
                $this->getEmailFormComponent()
                ->disabled(),
                Forms\Components\TextInput::make('phone')
                    ->label('Teléfono')
                    ->numeric()
                    ->length(9)
                    ->required(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}