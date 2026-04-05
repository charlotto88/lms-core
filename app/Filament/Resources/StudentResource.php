<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
        {
            return $form
                ->schema([
                    \Filament\Forms\Components\Section::make('Personal Information')
                        ->schema([
                            \Filament\Forms\Components\TextInput::make('first_name')
                                ->required(),
                            \Filament\Forms\Components\TextInput::make('last_name')
                                ->required(),
                            \Filament\Forms\Components\TextInput::make('student_id')
                                ->label('Student ID Number')
                                ->required()
                                ->unique(ignoreRecord: true),
                            \Filament\Forms\Components\TextInput::make('email')
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true),
                            // Add this to your Personal Information section
                            \Filament\Forms\Components\TextInput::make('password')
                                ->password()
                                ->revealable() // Adds the "eye" icon to see what you're typing
                                ->dehydrated(fn ($state) => filled($state)) // ONLY sends to DB if you type something
                                ->required(fn (string $operation): bool => $operation === 'create') // Required for new students, optional for edits
                                ->helperText('Leave blank to keep the current password.'),
                        ])->columns(2),

                    \Filament\Forms\Components\Section::make('Academic Enrollment')
                        ->schema([
                            \Filament\Forms\Components\Select::make('courses')
                                ->relationship('courses', 'title') // Links to the many-to-many relationship
                                ->multiple() // One student can be in many courses
                                ->preload()
                                ->searchable(),
                        ]),
                ]);
        }
    public static function table(Table $table): Table
        {
            return $table
                ->columns([
                    \Filament\Tables\Columns\TextColumn::make('student_id')->label('ID')->sortable()->searchable(),
                    \Filament\Tables\Columns\TextColumn::make('first_name')->searchable(),
                    \Filament\Tables\Columns\TextColumn::make('last_name')->searchable(),
                    \Filament\Tables\Columns\TextColumn::make('email'),
                    \Filament\Tables\Columns\TextColumn::make('courses_count')
                        ->counts('courses')
                        ->label('Enrolled Courses')
                        ->badge(),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
