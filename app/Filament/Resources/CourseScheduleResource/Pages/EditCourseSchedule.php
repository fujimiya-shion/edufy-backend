<?php

namespace App\Filament\Resources\CourseScheduleResource\Pages;

use App\Filament\Resources\CourseScheduleResource;
use App\Traits\UsesFilamentServiceCrud;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourseSchedule extends EditRecord
{
    use UsesFilamentServiceCrud;
    protected static string $resource = CourseScheduleResource::class;

}
