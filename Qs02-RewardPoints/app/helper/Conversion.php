<?php

declare(strict_types=1);

namespace App\Helper;

use App\Model\Currency;

class Conversion
{
    public function convertIntToDecimal(float $value): float
    {
        return $value * (1 / 100);
    }


    public function convertDecimalToInt(float $value): float
    {
        return $value * 100;
    }


    public function convertToUSD(string $currencyIn, float $value): float
    {
        $intVal = $this->convertDecimalToInt($value);
        $result = (new Currency())->findCurrency($currencyIn);
        $converted = $intVal / floatval($result['conversion_rate_usd']);

        return $this->convertIntToDecimal($converted);
    }
}
