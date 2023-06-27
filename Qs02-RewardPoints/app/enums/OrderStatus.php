<?php

namespace App\Enums;

enum OrderStatus: int
{
    case Pending = 1;
    case InProgress = 2;
    case Complete = 3;

    public function toSring()
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::InProgress => 'In Progress',
            self::Complete => 'Complete',
        };
    }
}
