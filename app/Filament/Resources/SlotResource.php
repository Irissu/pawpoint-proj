<?php

namespace App\Filament\Resources;

use App\Enums\RoleUsers;
use App\Filament\Resources\SlotResource\Pages;
use App\Filament\Resources\SlotResource\RelationManagers;
use App\Models\Slot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\User;

class SlotResource extends Resource
{
    protected static ?string $model = Slot::class;
    protected static ?string $navigationLabel = 'Slots';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->options([
                        'available' => 'Disponible',
                        'booked' => 'Reservado',
                    ])
                    ->label('Estado')
                    ->hiddenOn('create')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Fecha')
      /*               ->formatStateUsing(function ($state) {
                        return \Carbon\Carbon::parse($state)->format('d/m/Y');
                    }) */
                    ->dateTime()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Hora de inicio')
/*                     ->formatStateUsing(function ($state) {
                        return \Carbon\Carbon::parse($state)->format('H:i');
                    }) */
                    ->dateTime()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Hora de fin')
        /*             ->formatStateUsing(function ($state) {
                        return \Carbon\Carbon::parse($state)->format('H:i'); 
                    }) */
                    ->dateTime()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vet.name')
                    ->label('Veterinario')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('vet_id')
                    ->label('Veterinario')
                    ->options(fn () => User::where('role', RoleUsers::Vet)->pluck('name', 'id')->toArray()),    
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
            'index' => Pages\ListSlots::route('/'),
            'create' => Pages\CreateSlot::route('/create'),
            'edit' => Pages\EditSlot::route('/{record}/edit'),
        ];
    }
}
