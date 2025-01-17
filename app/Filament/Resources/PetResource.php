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
use Illuminate\Support\Facades\Auth;

class PetResource extends Resource
{
    protected static ?string $model = Pet::class;
    protected static ?string $navigationLabel = 'Mascotas';
    protected static ?string $modelLabel = 'Mascota';
    protected static ?string $pluralmodelLabel = 'Mascotas';
    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?int $navigationSort = 3;

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
                ->native(false)
                ->label('Tipo')
                ->required(),
                Forms\Components\TextInput::make('breed')
                ->label('Raza'),
                Forms\Components\Datepicker::make('date_of_birth')
                ->label('Nacimiento')
                ->required()
                ->maxDate(now()),
                Forms\Components\TextInput::make('weight')
                ->label('Peso')
                ->step(0.01)
                ->numeric(),
                Forms\Components\Select::make('owner_id')
                ->hidden(!Auth::user()->isAdmin() && !Auth::user()->isVet()) 
                ->relationship('owner', 'name', function (Builder $query) { // solo muestra los dueños, unicos que pueden tener mascotas
                    return $query->where('role', RoleUsers::User);
                }) 
                ->searchable()
                ->preload() // carga los datos de la relación, si hay pocos datos esta bien, si hay muchos datos es mejor no usarlo. De momento lo dejo así
                ->createOptionForm([
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
                    ->numeric()
                    ->length(9)
                    ->required(),
                    Forms\Components\TextInput::make('address')
                    ->label('Dirección'),
                    // rol automaticamente owner
                    Forms\Components\Hidden::make('role')
                    ->default(RoleUsers::User),
                ])
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
                Tables\Columns\TextColumn::make('type')
                ->label('Tipo')
                ->sortable()
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'dog' => 'danger',
                    'cat' => 'info',
                })
                ->formatStateUsing(function ($state) {
                    return $state === 'dog' ? 'Perro' : 'Gato';
                }),
                Tables\Columns\TextColumn::make('breed')
                ->label('Raza'),
                Tables\Columns\TextColumn::make('owner.name') // solo debería ser visible para admin y vet
                ->hidden(!Auth::user()->isAdmin() && !Auth::user()->isVet())
                ->label('Dueño')
                ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                ->options([
                    'dog' => 'Perro',
                    'cat' => 'Gato',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->iconButton(),
                Tables\Actions\DeleteAction::make()
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

        public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        if (Auth::user()->isOwner()) {
            $query->where('owner_id', Auth::id());
        }
        return $query;
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
