<?php

namespace App\Enums;

enum CourseLevel: int
{
    case Beginner = 0;
    case Intermediate = 1;
    case Advanced = 2;

    public function label(): string
    {
        return match ($this) {
            self::Beginner => 'Beginner',
            self::Intermediate => 'Intermediate',
            self::Advanced => 'Advanced',
        };
    }
}
