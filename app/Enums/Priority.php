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

    public function color(): string
    {
        return match($this) {
            self::High => 'danger',    
            self::Medium => 'warning',
            self::Low => 'info',   
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::High => 'heroicon-o-exclamation-triangle',
            self::Medium => 'heroicon-o-minus',
            self::Low => 'heroicon-o-chevron-down',
        };
    }
}
