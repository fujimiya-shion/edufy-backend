<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\Concerns\UsesServiceCrud;
use App\Filament\Resources\CourseResource;
use App\Traits\UsesFilamentServiceCrud;
use Filament\Resources\Pages\EditRecord;

class EditCourse extends EditRecord
{
    use UsesFilamentServiceCrud;

    protected static string $resource = CourseResource::class;
}
