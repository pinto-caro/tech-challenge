<?php

namespace App\Enums;

enum UserRole: string
{
    case Supervisor = 'supervisor';
    case Technician = 'technician';
}
