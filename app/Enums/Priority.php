<?php

namespace App\Enums;

enum Priority: int
{
    case High = 1;
    case Medium = 2;
    case Low = 3;

    public function label(): string
    {
        return match($this) {
            self::High => 'High',
            self::Medium => 'Medium',
            self::Low => 'Low',
        };
    }
}
