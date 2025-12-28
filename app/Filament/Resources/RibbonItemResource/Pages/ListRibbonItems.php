<?php

namespace App\Filament\Resources\RibbonItemResource\Pages;

use App\Filament\Resources\RibbonItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRibbonItems extends ListRecords
{
    protected static string $resource = RibbonItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
