<?php

namespace App\Filament\Resources\RibbonItemResource\Pages;

use App\Filament\Resources\RibbonItemResource;
use App\Traits\UsesFilamentServiceCrud;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRibbonItem extends CreateRecord
{
    use UsesFilamentServiceCrud;
    protected static string $resource = RibbonItemResource::class;
}
