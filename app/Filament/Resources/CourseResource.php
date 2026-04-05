<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Str;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Academic Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // TOP BAR: Course Identity (Modern, Slim)
                Section::make()
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('title')
                                ->label('Course Name')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                            TextInput::make('course_code')->label('Course Code')->required(),
                            Select::make('category_id')
                                ->label('Department')
                                ->relationship('category', 'name')
                                ->required(),
                        ]),
                    ])->compact(),

                // THE CURRICULUM CANVAS
                Section::make('Curriculum Builder')
                    ->description('Organize your structure and build lesson content using blocks.')
                    ->schema([
                        Repeater::make('chapters')
                            ->relationship()
                            ->label('Chapter')
                            ->addActionLabel('Add New Chapter')
                            ->itemLabel(fn ($state) => $state['title'] ?? 'Untitled Chapter')
                            ->collapsible()
                            ->inset() // Flattens the UI, removes "Box-in-Box" look
                            ->schema([
                                TextInput::make('title')->placeholder('Chapter Title (e.g. Chapter 1)')->required(),

                                // MODULES
                                Repeater::make('modules')
                                    ->relationship()
                                    ->label('Modules')
                                    ->addActionLabel('Add Module')
                                    ->itemLabel(fn ($state) => $state['title'] ?? 'Untitled Module')
                                    ->collapsible()
                                    ->collapsed()
                                    ->inset()
                                    ->schema([
                                        TextInput::make('title')->placeholder('Module Title (e.g. Module 1.1)')->required(),

                                        // LESSONS: The Block Builder Canvas
                                        Repeater::make('materials')
                                            ->relationship()
                                            ->label('Lessons')
                                            ->addActionLabel('Add Lesson')
                                            ->itemLabel(fn ($state) => $state['title'] ?? 'Untitled Lesson')
                                            ->collapsible()
                                            ->collapsed()
                                            ->schema([
                                                TextInput::make('title')
                                                    ->label('Lesson Name')
                                                    ->required()
                                                    ->columnSpanFull(),

                                                // THE BLOCK BUILDER: Quick Actions for Content
                                                Builder::make('content_blocks') // Ensure this is a JSON column in your DB
                                                    ->label('Lesson Designer')
                                                    ->blocks([
                                                        // Block 1: Text
                                                        Block::make('text')
                                                            ->label('Massive Text / Paragraphs')
                                                            ->icon('heroicon-m-document-text')
                                                            ->schema([
                                                                RichEditor::make('text_content')
                                                                    ->label('Body')
                                                                    ->required()
                                                                    ->fileAttachmentsDirectory('lessons'),
                                                            ]),

                                                        // Block 2: Video
                                                        Block::make('video')
                                                            ->label('Video Player')
                                                            ->icon('heroicon-m-play-circle')
                                                            ->schema([
                                                                TextInput::make('url')
                                                                    ->label('Video Link (YouTube/Vimeo)')
                                                                    ->placeholder('https://...')
                                                                    ->required(),
                                                            ]),

                                                        // Block 3: Files
                                                        Block::make('file')
                                                            ->label('File Download')
                                                            ->icon('heroicon-m-paper-clip')
                                                            ->schema([
                                                                FileUpload::make('file_path')
                                                                    ->label('Attachment')
                                                                    ->directory('course-materials')
                                                                    ->required(),
                                                            ]),
                                                    ])
                                                    ->addActionLabel('Add Content Piece')
                                                    ->blockNumbers(false)
                                                    ->collapsible()
                                                    ->collapsed()
                                                    ->columnSpanFull(),
                                            ])
                                            ->orderColumn('sort_order'),
                                    ])
                                    ->orderColumn('sort_order'),
                            ])
                            ->orderColumn('sort_order'),
                    ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course_code')->label('Code')->weight('bold'),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('category.name')->label('Dept')->badge(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}