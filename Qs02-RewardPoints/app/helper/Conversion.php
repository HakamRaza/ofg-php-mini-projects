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

    public function cleanDecimalToInt(float $value): int
    {
        return intval(ceil($value));
    }

    public function convertCurrencyToUSD(int $currencyId, int $value): float|null
    {
        $result = (new Currency())->findCurrency($currencyId);

        if (!$result) return null;

        $converted = $value / floatval($result['conversion_rate_usd']);

        return $this->convertIntToDecimal($converted);
    }

    /**
     * 1 pt = USD 0.01
     * i.e. If user want to claim USD 1.00, he needs 100 pts
     * All point stored in DB already converted to USD * 100
     */
    public function convertPointClaimToPointDBRate(int $currencyId, int $pointClaimed)
    {
        $UsdRate = $this->convertCurrencyToUSD($currencyId, $pointClaimed);

        if (!$UsdRate) return null;

        return $UsdRate * 100;
    }

    /**
     * i.e If user paid USD 100 complete, get 100 pts
     * All point stored in DB already converted to USD * 100
     */
    public function convertPaymentToPointDBRate(int $currencyId, int $totalSales)
    {
        $UsdRate = $this->convertCurrencyToUSD($currencyId, $totalSales);

        if (!$UsdRate) return null;

        $clean = $this->cleanDecimalToInt($UsdRate);

        return $clean * 1;
    }
}
