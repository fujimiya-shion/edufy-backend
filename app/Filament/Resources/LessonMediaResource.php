<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LessonMediaResource\Pages;
use App\Models\LessonMedia;
use App\Services\Contracts\Lesson\ILessonMediaService;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables;
use Illuminate\Support\Facades\Storage;

class LessonMediaResource extends BaseResource
{
    protected static ?string $model = LessonMedia::class;
    protected static string $serviceContract = ILessonMediaService::class;

    protected static ?string $navigationIcon = 'heroicon-o-play-circle';
    protected static ?string $navigationGroup = 'Courses';
    protected static ?int $navigationSort = 6;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Section::make('Lesson Media')
                ->schema([
                    Forms\Components\Select::make('lesson_id')
                        ->relationship('lesson', 'title')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('course_media_id')
                        ->label('Course Media (video)')
                        ->relationship('courseMedia', 'title')
                        ->searchable()
                        ->preload()
                        ->helperText('Pick an existing CourseMedia, or create a new one here.')
                        ->createOptionForm([
                            Forms\Components\Select::make('course_id')
                                ->relationship('course', 'title')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Forms\Components\TextInput::make('title')
                                ->required()
                                ->maxLength(255),

                            Forms\Components\Select::make('disk')
                                ->options([
                                    'public' => 'public',
                                    'local' => 'local',
                                ])
                                ->default('public')
                                ->required(),

                            Forms\Components\FileUpload::make('original_path')
                                ->label('Original video')
                                ->disk(fn (Get $get) => $get('disk') ?: 'public')
                                ->directory('course-media/original')
                                ->visibility('public')
                                ->acceptedFileTypes([
                                    'video/mp4',
                                    'video/quicktime',
                                    'video/x-matroska',
                                    'video/webm',
                                ])
                                ->maxSize(512000) // 500MB
                                ->required()
                                ->afterStateUpdated(function (?string $state, Set $set, Get $get) {
                                    if (!$state) {
                                        $set('original_mime', null);
                                        $set('original_size', null);
                                        return;
                                    }

                                    $disk = $get('disk') ?: 'public';

                                    try {
                                        $set('original_mime', Storage::disk($disk)->mimeType($state));
                                        $set('original_size', Storage::disk($disk)->size($state));
                                    } catch (\Throwable $e) {
                                        // ignore
                                    }
                                }),

                            Forms\Components\TextInput::make('original_mime')
                                ->disabled()
                                ->dehydrated()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('original_size')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(),

                            Forms\Components\TextInput::make('duration_seconds')
                                ->numeric()
                                ->minValue(0),

                            Forms\Components\TextInput::make('status')
                                ->maxLength(50)
                                ->default('ready'),

                            Forms\Components\TextInput::make('sort_order')
                                ->numeric()
                                ->default(0),

                            Forms\Components\KeyValue::make('meta'),
                        ])
                        // IMPORTANT: require your LessonMedia model to allow null course_media_id if you want
                        ->required(),

                    Forms\Components\TextInput::make('title')
                        ->maxLength(255)
                        ->helperText('Optional: display title override for this lesson media.'),

                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('lesson.title')->label('Lesson')->limit(40),
            Tables\Columns\TextColumn::make('courseMedia.title')->label('Media')->limit(40),
            Tables\Columns\TextColumn::make('title')->label('Override title')->toggleable(),
            Tables\Columns\TextColumn::make('sort_order')->sortable(),
            Tables\Columns\TextColumn::make('created_at')->since()->toggleable(),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make()
                ->action(fn ($record) => static::getService()->delete($record->id)),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLessonMedia::route('/'),
            'create' => Pages\CreateLessonMedia::route('/create'),
            'edit' => Pages\EditLessonMedia::route('/{record}/edit'),
        ];
    }
}
