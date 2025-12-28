<?php

namespace App\Enums;

enum TeacherRole: int
{
    case Lead = 0;
    case Assistant = 1;

    public function label(): string
    {
        return match ($this) {
            self::Lead => 'Lead',
            self::Assistant => 'Assistant',
        };
    }
}
