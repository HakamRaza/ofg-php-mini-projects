<?php

namespace App\Enums;

enum Currency
{
    case USD;
    case MYR;

    public function string()
    {
        return match ($this) {
            self::USD => "United States",
            self::MYR => "Malaysia"
        };
    }

    public function rate()
    {
        return match ($this) {
            self::USD => 1.000000,
            self::MYR => 4.000000
        };
    }

    public function value()
    {
        return match ($this) {
            self::USD => 1,
            self::MYR => 2
        };
    }
}
