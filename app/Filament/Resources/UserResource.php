<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use App\Enums\RoleUsers;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\public\storage;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Usuarios';
    protected static ?string $modelLabel = 'Usuario';
    protected static ?string $pluralmodelLabel = 'Usuarios';
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->label('Nombre')
                ->minLength(2)
                ->maxLength(50)
                ->required(),
            Forms\Components\TextInput::make('surname')
                ->label('Apellidos')
                ->required(),
            Forms\Components\TextInput::make('email')
                ->required(),
            Forms\Components\TextInput::make('password')
                ->password()
                ->revealable()
                ->hiddenOn('edit')
                ->required(),
            Forms\Components\TextInput::make('phone')
                ->label('Teléfono')
                // ->tel() ?
                ->numeric()
                ->length(9)
                ->required(),
            Forms\Components\TextInput::make('address')
                ->label('Dirección'),
            Forms\Components\Textarea::make('bio')
                ->label('Biografía'),
             Forms\Components\Select::make('role')->options([
                'admin' => 'Admin',
                'vet' => 'Veterinario',
                'owner' => 'Dueño',
            ]) 
                ->label('Rol')
                ->default('owner')
                ->required(),
                Forms\Components\FileUpload::make('img_path')
                ->label('Avatar')
                ->image()
                ->imageEditor()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('img_path')
                    ->label('Avatar')
                    ->width(60)
                    ->height(60)
                    // quiero ir a esta carpeta en defaultImage: [C:\xampp\htdocs\pawpointproj\public\storage]
                    ->defaultImageUrl(url(asset('storage/default-user.jpg')))
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('surname')
                    ->label('Apellidos'),
                Tables\Columns\TextColumn::make('email'), 
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Rol')
                    ->hidden(!Auth::user()->isAdmin() && !Auth::user()->isVet()) 
                    ->formatStateUsing(function ($state) {
                        return $state->value === RoleUsers::Admin->value ? 'Admin' : ($state->value === RoleUsers::Vet->value ? 'Veterinario' : 'Cliente');
                    })
                    ->badge()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->size('xl')
                ->iconButton(),
                Tables\Actions\DeleteAction::make()
                ->size('xl')
                ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
/*         if (Auth::user()->isAdmin()) {
            return true;
        } else {
            return false;
        } */

        return Auth::user()->isAdmin();
    }
}
