<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Services\Contracts\Order\IOrderService;
use Filament\Forms;
use Filament\Tables;

class OrderResource extends BaseResource
{
    protected static ?string $model = Order::class;
    protected static string $serviceContract = IOrderService::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Commerce';
    protected static ?int $navigationSort = 10;
    protected static ?string $modelLabel = 'Order';
    protected static ?string $pluralModelLabel = 'Orders';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('order_number')->disabled()->dehydrated(false),
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()->preload()
                ->required(),
            Forms\Components\TextInput::make('currency')->maxLength(10),
            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'pending',
                    'processing' => 'processing',
                    'completed' => 'completed',
                    'cancelled' => 'cancelled',
                ]),
            Forms\Components\Select::make('payment_status')
                ->options([
                    'unpaid' => 'unpaid',
                    'paid' => 'paid',
                    'failed' => 'failed',
                    'refunded' => 'refunded',
                ]),
            Forms\Components\TextInput::make('payment_method')->maxLength(100),
            Forms\Components\TextInput::make('subtotal')->numeric(),
            Forms\Components\TextInput::make('discount_total')->numeric(),
            Forms\Components\TextInput::make('tax_total')->numeric(),
            Forms\Components\TextInput::make('total')->numeric(),
            Forms\Components\Textarea::make('note')->columnSpanFull(),
            Forms\Components\KeyValue::make('meta')->keyLabel('key')->valueLabel('value')->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')->label('Order #')->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('User')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('payment_status')->badge(),
                Tables\Columns\TextColumn::make('total')->money(fn ($record) => $record->currency ?? 'USD'),
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
            'index'  => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
