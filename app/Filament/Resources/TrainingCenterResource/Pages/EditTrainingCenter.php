<?php

namespace App\Filament\Resources\TrainingCenterResource\Pages;

use App\Filament\Resources\TrainingCenterResource;
use App\Traits\UsesFilamentServiceCrud;
use Filament\Resources\Pages\EditRecord;

class EditTrainingCenter extends EditRecord
{
    use UsesFilamentServiceCrud;

    protected static string $resource = TrainingCenterResource::class;
}
