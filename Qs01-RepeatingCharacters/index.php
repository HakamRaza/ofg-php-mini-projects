<?php


class CharMath
{
    /**
     * Sample inputs to process
     */
    private $sampleInputs = [];


    /**
     * Calculate the number of repeating characters for deletions
     * 
     * @return int number of repeatition
     */
    private function getNumberOfCharRepeats(string $value): int
    {
        $wordLength = strlen($value);
        $numberOfRepetition = 0;

        for ($i = 1; $i < $wordLength; $i++) {
            if ($value[$i] == $value[$i - 1]) {
                $numberOfRepetition++;
            }
        }

        return $numberOfRepetition;
    }

    /**
     * Set $sampleInputs for process
     */
    public function setInputs(array $sampleInputs): CharMath
    {
        $this->sampleInputs = $sampleInputs;

        return $this;
    }

    /**
     * Get result
     */
    public function get(): void
    {
        echo "|"
            . str_pad("Bil", 4)
            . "|"
            . str_pad("Value", 15)
            . "|"
            . str_pad("Min", 3)
            . "|\n";

        echo str_pad('',  26, "-")
            . "\n";

        foreach ($this->sampleInputs as $key => $value) {
            $total = $this->getNumberOfCharRepeats($value);

            echo "|"
                . str_pad(($key + 1), 4)
                . "|"
                . str_pad($value, 15)
                . "|"
                . str_pad($total, 3)
                . "|\n";
        }
    }
}

$sampleInputs = [
    'AAAA',
    'BBBBB',
    'ABABABAB',
    'AAABBB',
];

$charMath = new CharMath();
$charMath->setInputs($sampleInputs)->get();
