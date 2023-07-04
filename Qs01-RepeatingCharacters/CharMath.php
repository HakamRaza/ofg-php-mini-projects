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
    public function get(): array
    {
        $output = [];

        foreach ($this->sampleInputs as $key => $value) {
            $output[] = $this->getNumberOfCharRepeats($value);
        }

        return $output;
    }
}