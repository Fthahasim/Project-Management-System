<?php

namespace App\Enums;

enum TaskStatus:string
{
    case Pending = 'pending';
    case Progress = 'in_progress';
    case Completed = 'completed';
}
