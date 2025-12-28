<?php

namespace App\Filament\Resources\CourseMediaResource\Pages;

use App\Filament\Resources\Concerns\UsesServiceCrud;
use App\Filament\Resources\CourseMediaResource;
use App\Traits\UsesFilamentServiceCrud;
use Filament\Resources\Pages\CreateRecord;

class CreateCourseMedia extends CreateRecord
{
    use UsesFilamentServiceCrud;

    protected static string $resource = CourseMediaResource::class;
}
