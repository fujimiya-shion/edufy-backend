<?php

namespace App\Filament\Resources\LessonResource\Pages;

use App\Filament\Resources\LessonResource;
use App\Traits\UsesFilamentServiceCrud;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLesson extends EditRecord
{
    use UsesFilamentServiceCrud;
    protected static string $resource = LessonResource::class;

}
