<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseMediaResource\Pages;
use App\Models\CourseMedia;
use App\Services\Contracts\Course\ICourseMediaService;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables;
use Illuminate\Support\Facades\Storage;

class CourseMediaResource extends BaseResource
{
    protected static ?string $model = CourseMedia::class;
    protected static string $serviceContract = ICourseMediaService::class;

    protected static ?string $navigationIcon = 'heroicon-o-play-circle';
    protected static ?string $navigationGroup = 'Courses';
    protected static ?int $navigationSort = 3;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Section::make('Basic')
                ->schema([
                    Forms\Components\Select::make('course_id')
                        ->relationship('course', 'title')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Textarea::make('description')
                        ->rows(4)
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Video Upload')
                ->schema([
                    Forms\Components\Select::make('disk')
                        ->options([
                            'public' => 'public',
                            'local' => 'local',
                            // add more disks if you have (s3, etc.)
                        ])
                        ->default('public')
                        ->required(),

                    Forms\Components\FileUpload::make('original_path')
                        ->label('Original video')
                        ->disk(fn (Get $get) => $get('disk') ?: 'public')
                        ->directory('course-media/original')
                        ->visibility('public')
                        ->preserveFilenames(false)
                        ->acceptedFileTypes([
                            'video/mp4',
                            'video/quicktime', // mov
                            'video/x-matroska', // mkv (tuỳ server)
                            'video/webm',
                        ])
                        // KB — tăng tuỳ nhu cầu (vd 500MB)
                        ->maxSize(512000)
                        ->required()
                        ->helperText('Upload video file. If you still get 12MB limit, increase PHP/Nginx upload limits.')
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
                        ->dehydrated() // still save to DB
                        ->maxLength(255),

                    Forms\Components\TextInput::make('original_size')
                        ->numeric()
                        ->disabled()
                        ->dehydrated()
                        ->helperText('Bytes'),

                    Forms\Components\TextInput::make('duration_seconds')
                        ->numeric()
                        ->minValue(0)
                        ->helperText('Optional (seconds). If you have job to detect duration, fill automatically there.'),

                    Forms\Components\TextInput::make('playback_manifest_path')
                        ->maxLength(2048)
                        ->helperText('Optional: HLS manifest path (master.m3u8) if you process to HLS.'),

                    Forms\Components\TextInput::make('processing_job_id')
                        ->maxLength(255),

                    Forms\Components\Textarea::make('failure_reason')
                        ->rows(2)
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Meta & Output')
                ->schema([
                    Forms\Components\Repeater::make('renditions')
                        ->schema([
                            Forms\Components\TextInput::make('height')->numeric(),
                            Forms\Components\TextInput::make('path')->maxLength(2048),
                        ])
                        ->defaultItems(0)
                        ->reorderable()
                        ->collapsed()
                        ->columnSpanFull(),

                    Forms\Components\Repeater::make('thumbnails')
                        ->schema([
                            Forms\Components\TextInput::make('time')->numeric()->helperText('Second'),
                            Forms\Components\TextInput::make('path')->maxLength(2048),
                        ])
                        ->defaultItems(0)
                        ->reorderable()
                        ->collapsed()
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('status')
                        ->maxLength(50)
                        ->default('ready')
                        ->helperText('e.g. queued / processing / ready / failed'),

                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0),

                    Forms\Components\KeyValue::make('meta')
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course.title')->label('Course')->limit(40),
                Tables\Columns\TextColumn::make('title')->searchable()->limit(40),
                Tables\Columns\TextColumn::make('original_mime')->label('MIME')->toggleable(),
                Tables\Columns\TextColumn::make('original_size')
                    ->label('Size')
                    ->formatStateUsing(fn ($state) => $state ? number_format((float) $state / 1024 / 1024, 2) . ' MB' : '-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration_seconds')->label('Duration')->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->since(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->action(fn ($record) => static::getService()->delete($record->id)),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourseMedia::route('/'),
            'create' => Pages\CreateCourseMedia::route('/create'),
            'edit' => Pages\EditCourseMedia::route('/{record}/edit'),
        ];
    }
}
