<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Traits\UsesFilamentServiceCrud;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    use UsesFilamentServiceCrud;
    protected static string $resource = OrderResource::class;
}
