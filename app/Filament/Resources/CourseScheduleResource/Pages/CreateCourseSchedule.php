<?php

namespace App\Filament\Resources\CourseScheduleResource\Pages;

use App\Filament\Resources\CourseScheduleResource;
use App\Traits\UsesFilamentServiceCrud;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCourseSchedule extends CreateRecord
{
    use UsesFilamentServiceCrud;
    protected static string $resource = CourseScheduleResource::class;
}
