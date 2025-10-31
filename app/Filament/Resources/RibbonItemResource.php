<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RibbonItemResource\Pages;
use App\Models\RibbonItem;
use App\Services\Contracts\Ribbon\IRibbonItemService;
use Filament\Forms;
use Filament\Tables;

class RibbonItemResource extends BaseResource
{
    protected static ?string $model = RibbonItem::class;
    protected static string $serviceContract = IRibbonItemService::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';
    protected static ?string $navigationGroup = 'Homepage';
    protected static ?int $navigationSort = 2;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('ribbon_id')
                ->relationship('ribbon', 'title')
                ->required(),
            Forms\Components\Select::make('course_id')
                ->relationship('course', 'title')
                ->searchable(),
            Forms\Components\TextInput::make('order')->numeric()->default(0),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('ribbon.title')->label('Ribbon'),
            Tables\Columns\TextColumn::make('course.title')->label('Course'),
            Tables\Columns\TextColumn::make('order')->sortable(),
            Tables\Columns\TextColumn::make('created_at')->since(),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make()
                ->action(fn ($record) => static::getService()->delete($record->id)),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRibbonItems::route('/'),
            'create' => Pages\CreateRibbonItem::route('/create'),
            'edit' => Pages\EditRibbonItem::route('/{record}/edit'),
        ];
    }
}
