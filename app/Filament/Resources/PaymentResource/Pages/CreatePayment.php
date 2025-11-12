<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Traits\UsesFilamentServiceCrud;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    use UsesFilamentServiceCrud;
    protected static string $resource = PaymentResource::class;
}
