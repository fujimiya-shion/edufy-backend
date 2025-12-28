<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Models\Teacher;
use App\Services\Contracts\Teacher\ITeacherService;
use Filament\Forms;
use Filament\Tables;

class TeacherResource extends BaseResource
{
    protected static ?string $model = Teacher::class;
    protected static string $serviceContract = ITeacherService::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Organization';
    protected static ?int $navigationSort = 2;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('training_center_id')
                ->relationship('trainingCenter', 'name')
                ->required(),
            Forms\Components\TextInput::make('full_name')->required(),
            Forms\Components\TextInput::make('slug'),
            Forms\Components\TextInput::make('email')->email(),
            Forms\Components\TextInput::make('phone'),
            Forms\Components\TextInput::make('title'),
            Forms\Components\FileUpload::make('avatar_path')
                ->disk('public')
                ->directory('teachers/avatars'),
            Forms\Components\Toggle::make('is_active')->default(true),
            Forms\Components\Textarea::make('bio')->columnSpanFull(),
            Forms\Components\KeyValue::make('skills')->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar_path')->circular(),
                Tables\Columns\TextColumn::make('full_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('trainingCenter.name')->label('Center'),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('email'),
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
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}
