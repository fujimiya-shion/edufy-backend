<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseScheduleResource\Pages;
use App\Models\CourseSchedule;
use App\Services\Contracts\Course\ICourseScheduleService;
use Filament\Forms;
use Filament\Tables;

class CourseScheduleResource extends BaseResource
{
    protected static ?string $model = CourseSchedule::class;
    protected static string $serviceContract = ICourseScheduleService::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Courses';
    protected static ?int $navigationSort = 4;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('course_id')
                ->relationship('course', 'title')
                ->required(),
            Forms\Components\Select::make('teacher_id')
                ->relationship('teacher', 'full_name'),
            Forms\Components\Select::make('day_of_week')
                ->options([
                    1 => 'Monday',
                    2 => 'Tuesday',
                    3 => 'Wednesday',
                    4 => 'Thursday',
                    5 => 'Friday',
                    6 => 'Saturday',
                    7 => 'Sunday',
                ]),
            Forms\Components\TimePicker::make('start_time'),
            Forms\Components\TimePicker::make('end_time'),
            Forms\Components\TextInput::make('timezone'),
            Forms\Components\TextInput::make('location'),
            Forms\Components\TextInput::make('room'),
            Forms\Components\DatePicker::make('active_from'),
            Forms\Components\DatePicker::make('active_to'),
            Forms\Components\Toggle::make('is_active')->default(true),
            Forms\Components\KeyValue::make('notes')->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('course.title')->label('Course')->sortable(),
            Tables\Columns\TextColumn::make('teacher.full_name')->label('Teacher'),
            Tables\Columns\TextColumn::make('day_of_week'),
            Tables\Columns\TextColumn::make('start_time')->time('H:i'),
            Tables\Columns\TextColumn::make('end_time')->time('H:i'),
            Tables\Columns\IconColumn::make('is_active')->boolean(),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make()
                ->action(fn ($record) => static::getService()->delete($record->id)),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourseSchedules::route('/'),
            'create' => Pages\CreateCourseSchedule::route('/create'),
            'edit' => Pages\EditCourseSchedule::route('/{record}/edit'),
        ];
    }
}
