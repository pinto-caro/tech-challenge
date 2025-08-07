<?php

namespace App\Enums;

enum Status: string
{
    case Created = 'Created';
    case InProgress = 'In progress';
    case Pending = 'Pending approval';
    case Approved = 'Approved';
    case Rejected = 'Rejected';

    public function color(): string
    {
        return match($this) {
            self::Created => 'gray',    
            self::InProgress => 'warning',
            self::Pending => 'info',   
            self::Approved => 'success',   
            self::Rejected => 'danger',   
        };
    }
}