<?php

namespace App\Filament\Resources\CourseMediaResource\Pages;

use App\Filament\Resources\CourseMediaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCourseMedia extends ListRecords
{
    protected static string $resource = CourseMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
