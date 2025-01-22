<?php

namespace App\Filament\Resources\PetResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MedicalRecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'medicalRecords';
    protected static ?string $navigationLabel = 'Historial Médico';
    protected static ?string $modelLabel = 'Historial Médico';
    protected static ?string $pluralmodelLabel = 'Historiales Médicos';
    protected static ?string $title = 'Historial Médico';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('date')
                    ->required()
                    ->label('Fecha')
                    ->columnSpan('full')
                    ->maxLength(255),
                Forms\Components\Select::make('vet_id')
                    ->required()
                    ->relationship('vet', 'name')
                    ->label('Veterinario'),
                Forms\Components\Textarea::make('summary')
                    ->required()
                    ->label('Diagnóstico')
                    ->columnSpan('full')
                    ->rows(3),
                Forms\Components\Textarea::make('treatment')
                    ->required()
                    ->label('Tratamiento')
                    ->columnSpan('full')
                    ->rows(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('date')
            ->modelLabel('Historial Médico')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Fecha'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                /* Tables\Actions\CreateAction::make(), */
            ])
            ->actions([
                /* Tables\Actions\EditAction::make(), */
                Tables\Actions\ViewAction::make()
                ->modalHeading('Resumen Visita'),
               /*  Tables\Actions\DeleteAction::make(), */
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
