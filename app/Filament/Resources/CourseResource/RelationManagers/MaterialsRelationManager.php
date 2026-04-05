<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaterialsRelationManager extends RelationManager
{
    protected static string $relationship = 'materials';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
        {
            return $table
                ->headerActions([
                    // This is the Udemy "Add Content" feel
                    Tables\Actions\CreateAction::make()
                        ->slideOver() // Makes it feel high-end
                        ->modalWidth('5xl')
                        ->label('Add New Lesson Content'),
                ])
                ->actions([
                    Tables\Actions\EditAction::make()
                        ->slideOver()
                        ->modalWidth('5xl'),
                ]);
        }
}
