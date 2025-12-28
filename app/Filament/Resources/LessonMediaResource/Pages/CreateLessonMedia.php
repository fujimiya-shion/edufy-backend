<?php

namespace App\Filament\Resources\LessonMediaResource\Pages;

use App\Filament\Resources\LessonMediaResource;
use App\Traits\UsesFilamentServiceCrud;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLessonMedia extends CreateRecord
{
    use UsesFilamentServiceCrud;
    protected static string $resource = LessonMediaResource::class;
}
