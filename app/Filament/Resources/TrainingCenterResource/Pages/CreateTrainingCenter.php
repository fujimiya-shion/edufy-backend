<?php

namespace App\Filament\Resources\TrainingCenterResource\Pages;

use App\Filament\Resources\Concerns\UsesServiceCrud;
use App\Filament\Resources\TrainingCenterResource;
use App\Traits\UsesFilamentServiceCrud;
use Filament\Resources\Pages\CreateRecord;

class CreateTrainingCenter extends CreateRecord
{
    
    use UsesFilamentServiceCrud;
    protected static string $resource = TrainingCenterResource::class;
}
