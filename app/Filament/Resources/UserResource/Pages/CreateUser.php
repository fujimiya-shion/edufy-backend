<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Traits\UsesFilamentServiceCrud;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    use UsesFilamentServiceCrud;
    protected static string $resource = UserResource::class;
}
