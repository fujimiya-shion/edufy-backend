<?php

namespace App\Enums;

enum MediaStatus: int
{
    case Uploaded = 0;
    case Processing = 1;
    case Ready = 2;
    case Failed = 3;

    public function label(): string
    {
        return match ($this) {
            self::Uploaded => 'Uploaded',
            self::Processing => 'Processing',
            self::Ready => 'Ready',
            self::Failed => 'Failed',
        };
    }
}
