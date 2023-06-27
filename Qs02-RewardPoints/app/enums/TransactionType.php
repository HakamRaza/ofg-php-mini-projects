<?php

namespace App\Enums;

enum TransactionType: Int
{
    case Debit = 1;
    case Credit = 2;


    public function toSring()
    {
        return match ($this) {
            self::Debit => 'Debit',
            self::Credit => 'Credit',
        };
    }
}
?>