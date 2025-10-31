<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LessonMediaResource\Pages;
use App\Models\LessonMedia;
use App\Services\Contracts\Lesson\ILessonMediaService;
use Filament\Forms;
use Filament\Tables;

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
            Forms\Components\Select::make('lesson_id')
                ->relationship('lesson', 'title')
                ->required(),
            Forms\Components\Select::make('course_media_id')
                ->relationship('courseMedia', 'title'),
            Forms\Components\TextInput::make('title'),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('lesson.title')->label('Lesson'),
            Tables\Columns\TextColumn::make('courseMedia.title')->label('Media'),
            Tables\Columns\TextColumn::make('title'),
            Tables\Columns\TextColumn::make('sort_order')->sortable(),
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
