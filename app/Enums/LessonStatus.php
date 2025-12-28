<?php

namespace App\Enums;

enum LessonStatus: int
{
    case Draft = 0;
    case Published = 1;

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Published => 'Published',
        };
    }
}
