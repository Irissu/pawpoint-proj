<?php

namespace App\Filament\Resources;

use App\Enums\RoleUsers;
use App\Filament\Resources\ScheduleResource\Pages;
use App\Filament\Resources\ScheduleResource\RelationManagers;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Horarios';
    protected static ?string $modelLabel = 'Horario';
    protected static ?string $pluralmodelLabel = 'Horarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('vet_id')
                ->hidden(fn () => !Auth::user()->isAdmin()) // si no es admin, no se muestra
                ->label('Veterinario')
                ->relationship('vet', 'name', function (Builder $query) {
                    return $query->where('role', RoleUsers::Vet);
                })
                ->required(fn () => Auth::user()->isAdmin()), // Solo requerido si es admin
                Forms\Components\Select::make('day_of_week')
                ->label('Día de la semana')
                ->options([
                    1 => 'Lunes',
                    2 => 'Martes',
                    3 => 'Miércoles',
                    4 => 'Jueves',
                    5 => 'Viernes'
                ])
                ->required(),
                Forms\Components\TimePicker::make('start_time')
                ->label('Hora de inicio')
                ->seconds(false)
                ->rule('regex:/^(1[0-7]|10|18):(00|30)$/')
                ->helperText('Las horas deben ser en punto o y media (ej: 10:00 o 15:30)')
                ->validationMessages([
                    'regex' => 'El horario de apertura es de 10 a 18.
                     Deben ser franjas horarias a en punto o y media',
                ])
                ->required(),
                Forms\Components\TimePicker::make('end_time')
                ->label('Hora de fin')
                ->seconds(false)
                ->rule('regex:/^(1[0-7]|10|18):(00|30)$/')
                ->helperText('Las horas deben ser en punto o y media (ej: 10:00 o 15:30)')
                ->after('start_time')
                ->validationMessages([
                    'regex' => 'El horario de apertura es de 10 a 18.
                     Deben ser franjas horarias a en punto o y media',
                    'after' => 'La hora de fin debe ser mayor que la hora de inicio',
                ])
                ->required(),
                Forms\Components\Toggle::make('is_active')
                ->label('Activo')
                ->default(true),
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
                Tables\Columns\TextColumn::make('day_of_week')
                ->label('Día de la semana')
                ->formatStateUsing(function ($state) {
                    return $state == 0 ? 'Lunes' : ($state == 1 ? 'Martes' : ($state == 2 ? 'Miércoles' : ($state == 3 ? 'Jueves' : 'Viernes')));
                })
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                ->label('Hora de inicio')
       /*          ->formatStateUsing(function ($state) {
                    return \Carbon\Carbon::parse($state)->format('H:i');
                }) */
                ->dateTime()
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                ->label('Hora de fin')
     /*            ->formatStateUsing(function ($state) {
                    return \Carbon\Carbon::parse($state)->format('H:i');
                }) */
                ->dateTime()
                ->searchable()
                ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
            ->label('Activo')
            ->boolean()

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
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
