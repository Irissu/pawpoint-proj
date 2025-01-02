<?php
namespace App\Filament\Resources;

use App\Enums\RoleUsers;
use App\Filament\Resources\PetResource\Pages;
use App\Filament\Resources\PetResource\RelationManagers;
use App\Models\Pet;
use Filament\Forms;
use App\Models\User;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PetResource extends Resource
{
    protected static ?string $model = Pet::class;

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
                Forms\Components\Select::make('type')
                ->options([
                    'dog' => 'Perro',
                    'cat' => 'Gato',
                ])
                ->label('Tipo')
                ->required(),
                Forms\Components\TextInput::make('breed')
                ->label('Raza'),
                Forms\Components\Datepicker::make('date_of_birth')
                ->label('Nacimiento')
                ->maxDate(now()),
                Forms\Components\TextInput::make('weight')
                ->label('Peso')
                ->step(0.01)
                ->numeric(),
                Forms\Components\Select::make('owner_id')
                /** @disregard P1013 Undefined method. */
                ->hidden(!auth()->user()->isAdmin() && !auth()->user()->isVet()) 
                ->relationship('owner', 'name', function (Builder $query) {
                    return $query->where('role', RoleUsers::User);
                })
                ->required(),
                Forms\Components\Textarea::make('observations')
                ->label('Observaciones')
                ->rows(3)
                ->placeholder('Escribe aquí las observaciones...'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label('Nombre')
                ->searchable(),
                Tables\Columns\TextColumn::make('type')
                ->label('Tipo')
                ->sortable(),
                Tables\Columns\TextColumn::make('breed')
                ->label('Raza'),
                Tables\Columns\TextColumn::make('owner.name') // solo debería ser visible para admin y vet
                ->label('Dueño')
                ->searchable(),
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
            'index' => Pages\ListPets::route('/'),
            'create' => Pages\CreatePet::route('/create'),
            'edit' => Pages\EditPet::route('/{record}/edit'),
        ];
    }
}
