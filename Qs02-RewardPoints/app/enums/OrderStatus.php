<?php

namespace App\Enums;

enum OrderStatus
{
    case Pending;
    case InProgress;
    case Complete;
    case Cancel;

    public function toSring()
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::InProgress => 'In Progress',
            self::Complete => 'Complete',
            self::Cancel => 'Cancel',
        };
    }

    public function value()
    {
        return match ($this) {
            self::Pending => 1,
            self::InProgress => 2,
            self::Complete => 3,
            self::Cancel => 4,
        };
    }
}
