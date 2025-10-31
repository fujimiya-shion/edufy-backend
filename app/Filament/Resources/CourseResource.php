<?php

namespace App\Filament\Resources;

use App\Enums\CourseLevel;
use App\Enums\CourseStatus;
use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use App\Services\Contracts\Course\ICourseService;
use Filament\Forms;
use Filament\Tables;

class CourseResource extends BaseResource
{
    protected static ?string $model = Course::class;
    protected static string $serviceContract = ICourseService::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Courses';
    protected static ?int $navigationSort = 1;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('training_center_id')
                ->relationship('trainingCenter', 'name')
                ->required(),

            Forms\Components\TextInput::make('title')->required()->maxLength(255),
            Forms\Components\TextInput::make('slug')->maxLength(255),
            Forms\Components\TextInput::make('code')->maxLength(50),

            Forms\Components\Textarea::make('short_description')->rows(3),
            Forms\Components\RichEditor::make('description')->columnSpanFull(),

            Forms\Components\Select::make('level')
                ->options(collect(CourseLevel::cases())->mapWithKeys(fn ($c) => [$c->value => $c->name])->toArray())
                ->required(),

            Forms\Components\Select::make('status')
                ->options(collect(CourseStatus::cases())->mapWithKeys(fn ($c) => [$c->value => $c->name])->toArray())
                ->required(),

            Forms\Components\TextInput::make('duration_hours')->numeric(),
            Forms\Components\TextInput::make('capacity')->numeric(),

            Forms\Components\TextInput::make('tuition_fee')->numeric()->prefix('₫'),

            Forms\Components\DatePicker::make('start_date'),
            Forms\Components\DatePicker::make('end_date'),

            Forms\Components\FileUpload::make('cover_image_path')
                ->disk('public')
                ->directory('courses/covers'),

            Forms\Components\KeyValue::make('meta')->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('trainingCenter.name')->label('Center'),
                Tables\Columns\TextColumn::make('level'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('tuition_fee')->money('VND', true),
                Tables\Columns\TextColumn::make('start_date')->date(),
                Tables\Columns\TextColumn::make('end_date')->date(),
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
