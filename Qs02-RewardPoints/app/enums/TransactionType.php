<?php

namespace App\Enums;

enum TransactionType
{
    case Debit;
    case Credit;


    public function toSring()
    {
        return match ($this) {
            self::Debit => 'Debit',
            self::Credit => 'Credit',
        };
    }

    public function value()
    {
        return match ($this) {
            self::Debit => 1,
            self::Credit => 2,
        };
    }
}
