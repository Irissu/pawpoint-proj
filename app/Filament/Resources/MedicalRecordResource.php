<?php

namespace App\Filament\Resources;

use App\Enums\RoleUsers;
use App\Filament\Resources\MedicalRecordResource\Pages;
use App\Filament\Resources\MedicalRecordResource\RelationManagers;
use App\Models\MedicalRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\PetResource\RelationManagers\MedicalRecordsRelationManager;

class MedicalRecordResource extends Resource
{
    protected static ?string $model = MedicalRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Historial Médico';
    protected static ?string $modelLabel = 'Historial Médico';
    protected static ?string $pluralmodelLabel = 'Historiales Médicos';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('vet_id')
                    ->relationship('vet', 'name')
                    ->label('Veterinario'),
                Forms\Components\Select::make('pet_id')
                    ->relationship('pet', 'name')
                    ->label('Mascota'),
                Forms\Components\Select::make('owner_id')
                    ->relationship('owner', 'name')
                    ->label('Dueño'),
                Forms\Components\Datepicker::make('date')
                    ->label('Fecha'),
                Forms\Components\Textarea::make('summary')
                    ->label('Diagnóstico'),
                Forms\Components\Textarea::make('treatment')
                    ->label('Tratamiento'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vet.name')
                    ->label('Veterinario'),
                Tables\Columns\TextColumn::make('pet.name')
                    ->label('Mascota'),
                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Dueño'),
                Tables\Columns\TextColumn::make('date')
                    ->label('Fecha'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListMedicalRecords::route('/'),
            /* 'create' => Pages\CreateMedicalRecord::route('/create'), */
             'edit' => Pages\EditMedicalRecord::route('/{record}/edit'), 
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

    public static function canCreate(): bool
    {
        return false;
    }
}
