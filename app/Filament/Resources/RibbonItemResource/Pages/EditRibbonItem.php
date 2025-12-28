<?php

namespace App\Filament\Resources\RibbonItemResource\Pages;

use App\Filament\Resources\RibbonItemResource;
use App\Traits\UsesFilamentServiceCrud;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRibbonItem extends EditRecord
{
    use UsesFilamentServiceCrud;
    protected static string $resource = RibbonItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
