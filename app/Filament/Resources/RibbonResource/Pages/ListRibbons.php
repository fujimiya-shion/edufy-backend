<?php

namespace App\Filament\Resources\RibbonResource\Pages;

use App\Filament\Resources\RibbonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRibbons extends ListRecords
{
    protected static string $resource = RibbonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
