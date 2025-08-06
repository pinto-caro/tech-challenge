<?php

namespace App\Enums;

enum Status: string
{
    case Created = 'created';
    case InProgress = 'in_progress';
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

}
