<?

class Conversion
{

    public static function convertIntToDecimal(float $value): float
    {
        return $value * (1 / 100);
    }


    public static function convertDecimalToInt(float $value)
    {
        return $value * 100;
    }


    public function convertToUSD(string $currencyIn, float $value)
    {
        $intVal = $this->convertDecimalToInt($value);

        
    }



    
}





?>