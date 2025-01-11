<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use Filament\Forms;
use App\Enums\RoleUsers;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Symfony\Component\Yaml\Inline;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;
    protected static ?string $navigationLabel = 'Citas';
    protected static ?string $modelLabel = 'Cita';
    protected static ?string $pluralmodelLabel = 'Citas';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?int $navigationSort = 5;

    /* Crear datepicker para seleccionar el dia
   * añadir propiedad live() y radio options dinamicas para seleccionar el slot de la lista disponible para ese dia 
   */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('owner_id')
                    ->hidden(!Auth::user()->isAdmin() && !Auth::user()->isVet())
                    ->label('Dueño')
                    ->native(false)
                    ->afterStateUpdated(fn (Set $set) => $set('pet_id', null))
                    ->relationship('owner', 'name', function (Builder $query) { // solo muestra los dueños, unicos que pueden tener mascotas
                        return $query->where('role', RoleUsers::User);
                    })
                    ->live()
                    ->required(),
                    Forms\Components\Select::make('pet_name')
                    ->label('Mascota')
                    ->native(false)
                    ->options(function (callable $get) {
                        $ownerId = $get('owner_id');
                        if(Auth::user()->isAdmin() || Auth::user()->isVet()){
                            if ($ownerId) {
                                return \App\Models\Pet::where('owner_id', $ownerId)->pluck('name', 'id')->toArray();
                            }
                           
                        } else {
                            $user = Auth::user();
                            return $user->pets()->pluck('name', 'id')->toArray();
                        }
                    })
                    ->live()
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->label('Fecha')
                    ->native(false)
                    ->live()
                    ->displayFormat('d/m/Y')
                    ->closeOnDateSelection()
                    ->required()
                    ->minDate(now())
                    ->maxDate(now()->addDays(7)),
                /** consulta a la tabla slots donde: dado un dia (el dia seleccionado en el 'date' del DatePicker) devuelva si hay algun registro con 'status' = 'available' y devuelve el veterinario asociado a ese/esos registros */
                Forms\Components\Select::make('vet_id')
                    ->label('Veterinarios disponibles')
                    ->native(false)
                    ->live()
                    ->options(function (Get $get) {
                        $selectedDate = $get('date');
                        if ($selectedDate) {
                            return \App\Models\Slot::where('date', $selectedDate)
                                ->where('status', 'available')
                                ->pluck('vet_id')
                                ->unique()
                                ->mapWithKeys(function ($vetId) {
                                    $vet = \App\Models\User::find($vetId);
                                    return [$vetId => $vet ? $vet->name : ''];
                                })
                                ->toArray();
                        }
                        return [];
                    })
                    ->required(),
                Forms\Components\Radio::make('start_time')->inline(false) /* consulta a la tabla slots donde: se buscan los slots con 'status' = 'available' dado el veterinario seleccionado en el select anterior. devuelve las horas de inicio de cada registro */
                    ->label('Hora de la cita')
                    ->live()
                    ->required()
                    ->options(function (Get $get){
                        $selectedDate = $get('date');
                        $selectedVetId = $get('vet_id');
                        if ($selectedDate && $selectedVetId) { 
                        // Consultar los slots disponibles
                            $slots = \App\Models\Slot::where('date', $selectedDate)
                            ->where('vet_id', $selectedVetId)
                            ->where('status', 'available')
                            ->get();
                            // Generar las opciones para los botones de radio
                            $options = [];
                            foreach ($slots as $slot) {
                            $options[$slot->id] = $slot->start_time->format('H:i');
                            }
                            return $options;
                        }
                        return [];
                    })
                    ->inline()
                    ->inlineLabel(false),
                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->placeholder(`Aquí puede explicar el motivo de su cita`)
                    ->label('Descripción'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vet.name')
                    ->label('Veterinario')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pet_name')
                    ->label('Mascota'),
                Tables\Columns\TextColumn::make('pet_type')
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
                Tables\Columns\TextColumn::make('date')
                    ->label('Fecha')
                    ->formatStateUsing(function ($state) {
                        return \Carbon\Carbon::parse($state)->format('d/m/Y');
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Hora de inicio')
                    ->formatStateUsing(function ($state) {
                        return \Carbon\Carbon::parse($state)->format('H:i');
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Hora de fin')
                    ->formatStateUsing(function ($state) {
                        return \Carbon\Carbon::parse($state)->format('H:i');
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción'),
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
