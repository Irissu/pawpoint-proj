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

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                    ->formatStateUsing(function ($state) {
                        return $state->value === RoleUsers::Admin->value ? 'Admin' : ($state->value === RoleUsers::Vet->value ? 'Veterinario' : 'Cliente');
                    })
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
}
