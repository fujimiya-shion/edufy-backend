<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseMediaResource\Pages;
use App\Models\CourseMedia;
use App\Services\Contracts\Course\ICourseMediaService;
use Filament\Forms;
use Filament\Tables;

class CourseMediaResource extends BaseResource
{
    protected static ?string $model = CourseMedia::class;
    protected static string $serviceContract = ICourseMediaService::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Courses';
    protected static ?int $navigationSort = 3;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('course_id')
                ->relationship('course', 'title')
                ->required(),
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\Textarea::make('description'),
            Forms\Components\TextInput::make('disk'),
            Forms\Components\TextInput::make('original_path'),
            Forms\Components\TextInput::make('original_mime'),
            Forms\Components\TextInput::make('original_size')->numeric(),
            Forms\Components\TextInput::make('duration_seconds')->numeric(),
            Forms\Components\TextInput::make('playback_manifest_path'),
            Forms\Components\KeyValue::make('renditions'),
            Forms\Components\KeyValue::make('thumbnails'),
            Forms\Components\TextInput::make('status'),
            Forms\Components\TextInput::make('processing_job_id'),
            Forms\Components\Textarea::make('failure_reason'),
            Forms\Components\TextInput::make('sort_order')->numeric(),
            Forms\Components\KeyValue::make('meta'),
        ])->columns(2);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course.title')->label('Course'),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('original_mime'),
                Tables\Columns\TextColumn::make('duration_seconds'),
                Tables\Columns\TextColumn::make('status')->badge(),
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
