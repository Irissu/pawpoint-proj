<?php

namespace App\Filament\Resources;

use App;
use App\Enums\AppointmentStatus;
use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use App\Enums\RoleUsers;
use App\Models\MedicalRecord;
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
use Carbon\Carbon;
use Dom\Text;
use Exception;
use Filament\Notifications\Notification;
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
                    ->disabledOn('edit')
                    ->label('Dueño')
                    ->native(false)
                    ->validationMessages([
                        'required' => 'Selecciona un dueño para la cita',
                    ])
                    ->afterStateUpdated(fn(Set $set) => $set('pet_id', null))
                    ->relationship('owner', 'name', function (Builder $query) { // solo muestra los dueños, unicos que pueden tener mascotas
                        return $query->where('role', RoleUsers::User);
                    })
                    ->live()
                    ->required(),
                Forms\Components\Select::make('pet_name')
                    ->label('Mascota')
                    ->native(false)
                    ->disabledOn('edit')
                    ->validationMessages([
                        'required' => 'Selecciona una mascota para la cita',
                    ])
                    ->options(function (callable $get) {
                        $ownerId = $get('owner_id');
                        if (Auth::user()->isAdmin() || Auth::user()->isVet()) {
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
                    ->disabledOn('edit')
                    ->displayFormat('d/m/Y')
                    ->closeOnDateSelection()
                    ->required()
                    ->validationMessages([
                        'required' => 'Selecciona una fecha para la cita',
                    ])
                    ->minDate(now()->startOfDay())
                    ->maxDate(now()->addDays(7)),
                Forms\Components\Select::make('vet_id')
                    ->label('Veterinarios disponibles')
                    ->native(false)
                    ->live()
                    ->validationMessages([
                        'required' => 'Selecciona un veterinario disponible para la fecha seleccionada',
                    ])
                    ->hiddenOn('edit')
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
                Forms\Components\Radio::make('start_time')->inline(false) 
                    ->label('Hora de la cita')
                    ->live()
                    ->validationMessages([
                        'required' => 'Selecciona una hora para la cita',
                    ])
                    ->hiddenOn('edit')
                    ->required()
                    ->options(function (Get $get) {
                        $selectedDate = $get('date');
                        $selectedVetId = $get('vet_id');
                        $now = now(); 
                        if ($selectedDate && $selectedVetId) {
                            $slots = \App\Models\Slot::where('date', $selectedDate)
                                ->where('vet_id', $selectedVetId)
                                ->where('status', 'available')
                                ->get();
                            $options = [];
                            foreach ($slots as $slot) {
                                if (Carbon::parse($selectedDate)->toDateString() === $now->toDateString()) {
                                    // Se incluyen solo los horarios posteriores a la hora actual
                                    if ($slot->start_time->gt($now)) { // si la hora de inicio del slot es mayor que la hora actual
                                        $options[$slot->id] = $slot->start_time->format('H:i');
                                    }
                                } else {
                                    // Si no es el día actual, incluir todos los horarios
                                    $options[$slot->id] = $slot->start_time->format('H:i');
                                }
                            }

                            return $options;
                        }
                        return [];
                    })
                    ->inline()
                    ->inlineLabel(false),
                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->disabledOn('edit')
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
                    ->color(fn(string $state): string => match ($state) {
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
                    ->label('Hora')
                    ->formatStateUsing(function ($state) {
                        return \Carbon\Carbon::parse($state)->format('H:i');
                    })
                    ->searchable()
                    ->sortable(),
               /*  Tables\Columns\TextColumn::make('end_time')
                    ->label('Hora de fin')
                    ->formatStateUsing(function ($state) {
                        return \Carbon\Carbon::parse($state)->format('H:i'); 
                    })
                    ->searchable()
                    ->sortable(), */
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->sortable(),
               /*  Tables\Columns\TextColumn::make('description')
                    ->label('Descripción'), */
            ])
            ->filters([
                //
            ])
            ->actions([
 /*                Tables\Actions\EditAction::make()
                    ->iconButton(),
                    ->view() solo si es vet o admin */
              /*   Tables\Actions\DeleteAction::make()
                    ->iconButton(), */
                Tables\Actions\Action::make('Cancelar')
                ->modalHeading('Cancelar cita')
                ->requiresConfirmation()
                    ->modalDescription('¿Seguro que quieres cancelar la cita? Se enviará un email con los detalles de la cancelación.')
                    ->modalSubmitActionLabel('Si, cancelar')
                    ->modalCancelActionLabel('No, mantener')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->size('xl')
                    ->modalIcon('heroicon-o-x-mark')
                    ->modalIconColor('warning')
                    ->form([
                        TextInput::make('reason')
                            ->label('Motivo de la cancelación')
                            ->placeholder('Escribe aquí el motivo de la cancelación')
                            ->required(),
                    ])
                    ->action(function (Appointment $record, array $data) {
                        $record->update(['status' => 'cancelled']);

                        // Enviar notificación de cancelación
                        $record->owner->notify(new \App\Notifications\AppointmentCancelled($data['reason'], $record));
                    })
                    ->visible(fn(Appointment $record) => 
                    $record->status != AppointmentStatus::Cancelled &&
                    !MedicalRecord::where('appointment_id', $record->id)->exists()
                    )
                    ->hidden(!Auth::user()->isAdmin() && !Auth::user()->isVet()),
                Tables\Actions\Action::make('Confirmar')
                    ->action(function (Appointment $record) {
                        $record->update(['status' => 'confirmed']);

                                // Cambiar el estado del slot asociado a 'available'
                        if ($record->start_time) {
                            $slot = \App\Models\Slot::where('start_time', $record->start_time)
                                ->where('date', $record->date)
                                ->where('vet_id', $record->vet_id)
                                ->first();

                            if ($slot) {
                                $slot->update(['status' => 'available']);
                            }
                        }
                    })
                    ->visible(fn(Appointment $record) => $record->status != AppointmentStatus::Confirmed)
                    ->hidden(!Auth::user()->isAdmin() && !Auth::user()->isVet()) 
                    ->color('success')
                    ->size('xl')
                    ->icon('heroicon-o-check-circle'),
                    Tables\Actions\Action::make('Detalles')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->size('xl')
                    ->modalHeading('Detalles de la cita')
                    ->form([
                        Forms\Components\Grid::make(2) // Define el número de columnas
                            ->schema([
                                TextInput::make('vet_name')
                                    ->label('Veterinario'),
                                TextInput::make('owner_name')
                                    ->label('Dueño'),
                                TextInput::make('pet_name')
                                    ->label('Mascota'),
                                TextInput::make('pet_type')
                                    ->label('Tipo de Mascota'),
                                TextInput::make('date')
                                    ->label('Fecha'),
                                TextInput::make('start_time')
                                    ->label('Hora de inicio'),
                                TextInput::make('end_time')
                                    ->label('Hora de fin'),
                            ]),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3),
                    ])
                    ->fillForm(fn (Appointment $record): array => [
                        'vet_name' => $record->vet?->name ?? 'N/A',
                        'owner_name' => $record->owner?->name ?? 'N/A',
                        'pet_name' => $record->pet_name,
                        'pet_type' => $record->pet_type === 'dog' ? 'Perro' : 'Gato',
                        'date' =>  \Carbon\Carbon::parse($record->date)->format('d/m/Y'),
                        'start_time' =>  \Carbon\Carbon::parse($record->start_time)->format('H:i'),
                        'end_time' => \Carbon\Carbon::parse($record->end_time)->format('H:i'),
                        'description' => $record->description,
                    ])
                    ->disabledForm()
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false),

                    Tables\Actions\Action::make('Añadir')
                    ->icon('heroicon-o-document-text')
                    ->size('xl')
                    ->modalHeading('Registrar en el historial')
                    ->action(function (Appointment $record, array $data) {

                        // obtener la mascota
                        $pet = \App\Models\Pet::where('name', $record->pet_name)
                        ->where('owner_id', $record->owner_id)
                        ->first();
                        
                        if(!$pet){
                            throw new Exception('No se ha encontrado la mascota');
                        }

                        $existingRecord = MedicalRecord::where('appointment_id', $record->id)->first();
                        if ($existingRecord) {
                            Notification::make()
                                ->title('Historial ya registrado')
                                ->icon('heroicon-o-document-text')
                                ->danger()
                                ->body('El historial ya ha sido registrado para esta cita. No se puede registrar de nuevo.')
                                ->seconds(5)
                                ->send();
                            return;
                        }


                        MedicalRecord::create([
                            'appointment_id' => $record->id,
                            'pet_id' => $pet->id,
                            'vet_id' => $record->vet_id,
                            'owner_id' => $record->owner_id,
                            'date' => $record->date,
                            'start_time' => $record->start_time,
                            'end_time' => $record->end_time,
                            'summary' => $data['summary'],
                            'treatment' => $data['treatment'],
                        ]);
                    })
                    ->form([
                        Textarea::make('summary')
                            ->label('Resumen')
                            ->placeholder('Resumen de la cita')
                            ->rows(3)
                            ->required(),
                        Textarea::make('treatment')
                            ->label('Tratamiento')
                            ->rows(3)
                            ->placeholder('Tratamiento aplicado')
                            ->required(),
                    ])
                    ->visible(fn(Appointment $record) => !MedicalRecord::where('appointment_id', $record->id)->exists())
                    ->hidden(!Auth::user()->isAdmin() && !Auth::user()->isVet()) 
                    ->successNotification(
                        Notification::make()
                             ->success()
                             ->title('Historial registrado')
                             ->body('El historial ha sido actualizado con los datos de la última cita'),
                     )
                     ->successRedirectUrl(fn (): string => route('filament.dashboard.resources.medical-records.index')),

                     Tables\Actions\Action::make('Ver Historial')
                     ->icon('heroicon-o-document-text')
                     ->label('Ver')
                    ->size('xl')
                    ->modalHeading('Historial Médico')
                    ->form([
                        Textarea::make('summary')
                            ->label('Resumen')
                            ->disabled()
                            ->rows(3),
                        Textarea::make('treatment')
                            ->label('Tratamiento')
                            ->disabled()
                            ->rows(3),
                    ])
                    ->fillForm(fn(Appointment $record): array => [
                        'summary' => $record->medicalRecord?->summary,
                        'treatment' => $record->medicalRecord?->treatment,
                    ])
                    ->disabledForm()
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->visible(fn(Appointment $record) => MedicalRecord::where('appointment_id', $record->id)->exists()),

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
    } else if (Auth::user()->isVet()) {
        $query->where('vet_id', Auth::id());
    }

    return $query;
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
