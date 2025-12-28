<?php

namespace App\Filament\Resources\TrainingCenterResource\Pages;

use App\Filament\Resources\TrainingCenterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrainingCenters extends ListRecords
{
    protected static string $resource = TrainingCenterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
