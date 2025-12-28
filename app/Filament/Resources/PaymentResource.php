<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Services\Contracts\Payment\IPaymentService;
use Filament\Forms;
use Filament\Tables;

class PaymentResource extends BaseResource
{
    protected static ?string $model = Payment::class;
    protected static string $serviceContract = IPaymentService::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Commerce';
    protected static ?int $navigationSort = 11;
    protected static ?string $modelLabel = 'Payment';
    protected static ?string $pluralModelLabel = 'Payments';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('order_id')
                ->relationship('order', 'order_number')
                ->searchable()->preload()
                ->required(),
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()->preload()
                ->required(),
            Forms\Components\TextInput::make('provider')->maxLength(100)->required(),
            Forms\Components\TextInput::make('provider_payment_id')->maxLength(191),
            Forms\Components\TextInput::make('provider_charge_id')->maxLength(191),
            Forms\Components\TextInput::make('client_secret')->maxLength(191),
            Forms\Components\TextInput::make('amount')->numeric()->required(),
            Forms\Components\TextInput::make('currency')->maxLength(10),
            Forms\Components\Select::make('status')
                ->options([
                    'pending'   => 'pending',
                    'processing'=> 'processing',
                    'succeeded' => 'succeeded',
                    'failed'    => 'failed',
                    'canceled'  => 'canceled',
                ])->required(),
            Forms\Components\DateTimePicker::make('paid_at'),
            Forms\Components\KeyValue::make('payload')->keyLabel('key')->valueLabel('value')->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.order_number')->label('Order #')->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('User')->searchable(),
                Tables\Columns\TextColumn::make('provider'),
                Tables\Columns\TextColumn::make('amount')->money(fn ($record) => $record->currency ?? 'USD'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('paid_at')->since(),
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
            'index'  => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit'   => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
