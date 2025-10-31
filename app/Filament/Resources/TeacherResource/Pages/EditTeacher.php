<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\Concerns\UsesServiceCrud;
use App\Filament\Resources\TeacherResource;
use App\Traits\UsesFilamentServiceCrud;
use Filament\Resources\Pages\EditRecord;

class EditTeacher extends EditRecord
{
    use UsesFilamentServiceCrud;

    protected static string $resource = TeacherResource::class;
}
