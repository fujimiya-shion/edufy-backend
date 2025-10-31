<?php

namespace App\Filament\Resources\CourseMediaResource\Pages;

use App\Filament\Resources\Concerns\UsesServiceCrud;
use App\Filament\Resources\CourseMediaResource;
use App\Traits\UsesFilamentServiceCrud;
use Filament\Resources\Pages\EditRecord;

class EditCourseMedia extends EditRecord
{
    use UsesFilamentServiceCrud;

    protected static string $resource = CourseMediaResource::class;
}
