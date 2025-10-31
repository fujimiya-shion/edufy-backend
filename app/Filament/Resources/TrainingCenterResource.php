<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainingCenterResource\Pages;
use App\Models\TrainingCenter;
use App\Services\Contracts\TrainingCenter\ITrainingCenterService;
use Filament\Forms;
use Filament\Tables;

class TrainingCenterResource extends BaseResource
{
    protected static ?string $model = TrainingCenter::class;
    protected static string $serviceContract = ITrainingCenterService::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Organization';
    protected static ?int $navigationSort = 1;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('slug')->maxLength(255),
            Forms\Components\TextInput::make('code')->maxLength(50),
            Forms\Components\TextInput::make('email')->email(),
            Forms\Components\TextInput::make('phone'),
            Forms\Components\TextInput::make('website'),
            Forms\Components\Textarea::make('address_line1'),
            Forms\Components\TextInput::make('address_line2'),
            Forms\Components\TextInput::make('city'),
            Forms\Components\TextInput::make('state'),
            Forms\Components\TextInput::make('country'),
            Forms\Components\TextInput::make('postal_code'),
            Forms\Components\TextInput::make('timezone'),
            Forms\Components\KeyValue::make('meta')->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('code')->sortable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('city'),
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
            'index' => Pages\ListTrainingCenters::route('/'),
            'create' => Pages\CreateTrainingCenter::route('/create'),
            'edit' => Pages\EditTrainingCenter::route('/{record}/edit'),
        ];
    }
}
