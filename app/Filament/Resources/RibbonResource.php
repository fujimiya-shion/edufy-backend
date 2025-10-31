<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RibbonResource\Pages;
use App\Models\Ribbon;
use App\Services\Contracts\Ribbon\IRibbonService;
use Filament\Forms;
use Filament\Tables;

class RibbonResource extends BaseResource
{
    protected static ?string $model = Ribbon::class;
    protected static string $serviceContract = IRibbonService::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $navigationGroup = 'Homepage';
    protected static ?int $navigationSort = 1;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\TextInput::make('slug'),
            Forms\Components\Textarea::make('description'),
            Forms\Components\TextInput::make('status')->default('active'),
            Forms\Components\TextInput::make('order')->numeric()->default(0),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('title')->searchable(),
            Tables\Columns\TextColumn::make('slug'),
            Tables\Columns\TextColumn::make('status')->badge(),
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
            'index' => Pages\ListRibbons::route('/'),
            'create' => Pages\CreateRibbon::route('/create'),
            'edit' => Pages\EditRibbon::route('/{record}/edit'),
        ];
    }
}
