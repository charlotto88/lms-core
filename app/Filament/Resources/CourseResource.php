<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Course Details')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => 
                                        $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                                Forms\Components\TextInput::make('slug')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->unique(Course::class, 'slug', ignoreRecord: true),

                                Forms\Components\TextInput::make('course_code')
                                    ->label('Course Code (e.g. GVT01)')
                                    ->required(),

                                Forms\Components\Toggle::make('is_visible')
                                    ->label('Visible to Students')
                                    ->default(true),
                            ]),

                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('banner_image')
                            ->image()
                            ->directory('course-banners')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Curriculum Builder')
                    ->description('Organize your course into Chapters, Modules, and Lessons.')
                    ->schema([
                        Forms\Components\Repeater::make('chapters')
                            ->relationship('chapters')
                            ->orderColumn('sort_order')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Chapter Name (e.g. Hoofstuk 1)')
                                    ->required(),

                                Forms\Components\Repeater::make('modules')
                                    ->label('Modules / Units')
                                    ->relationship('modules')
                                    ->orderColumn('sort_order')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label('Module Name (e.g. Eenheid 1)')
                                            ->required(),

                                        Forms\Components\Repeater::make('materials')
                                            ->label('Lessons')
                                            ->relationship('materials')
                                            ->orderColumn('sort_order')
                                            ->columns(3) // Layout: 2/3 Title, 1/3 Duration
                                            ->schema([
                                                Forms\Components\TextInput::make('title')
                                                    ->label('Lesson Title')
                                                    ->required()
                                                    ->columnSpan(2)
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),

                                                Forms\Components\TextInput::make('duration')
                                                    ->label('Duration')
                                                    ->numeric()
                                                    ->suffix('min')
                                                    ->default(5)
                                                    ->required()
                                                    ->columnSpan(1),

                                                Forms\Components\Hidden::make('slug'),

                                                Forms\Components\Builder::make('content_blocks')
                                                    ->label('Lesson Content')
                                                    ->columnSpanFull()
                                                    ->blocks([
                                                        Forms\Components\Builder\Block::make('text')
                                                            ->schema([
                                                                Forms\Components\RichEditor::make('text_content')
                                                                    ->label('Content')
                                                                    ->required(),
                                                            ]),
                                                        Forms\Components\Builder\Block::make('video')
                                                            ->schema([
                                                                Forms\Components\TextInput::make('url')
                                                                    ->label('Video URL (YouTube/Vimeo)')
                                                                    ->required(),
                                                            ]),
                                                        Forms\Components\Builder\Block::make('image')
                                                            ->schema([
                                                                Forms\Components\FileUpload::make('image_path')
                                                                    ->image()
                                                                    ->required(),
                                                                Forms\Components\TextInput::make('alt_text'),
                                                            ]),
                                                    ]),
                                            ])
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
                                    ])
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
                            ])
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('banner_image')
                    ->circular(),
                Tables\Columns\TextColumn::make('course_code')
                    ->fontFamily('mono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_visible')
                    ->boolean()
                    ->label('Visible'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}