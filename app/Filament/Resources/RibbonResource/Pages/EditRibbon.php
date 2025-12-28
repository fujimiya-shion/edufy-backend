<?php

namespace App\Filament\Resources\RibbonResource\Pages;

use App\Filament\Resources\RibbonResource;
use App\Traits\UsesFilamentServiceCrud;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRibbon extends EditRecord
{
    use UsesFilamentServiceCrud;
    protected static string $resource = RibbonResource::class;
}
