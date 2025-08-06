<?php

namespace App\Enums;

enum Status: string
{
    case Created = 'Created';
    case InProgress = 'In progress';
    case Pending = 'Pending approval';
    case Approved = 'Approved';
    case Rejected = 'Rejected';
}
