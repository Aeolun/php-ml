<?php

declare(strict_types=1);

namespace Phpml\CrossValidation;

use Phpml\Dataset\ArrayDataset;
use Phpml\Dataset\Dataset;

class StratifiedRandomSplit extends RandomSplit
{
    /**
     * @param Dataset $dataset
     * @param float   $testSize
     */
    protected function splitDataset(Dataset $dataset, float $testSize)
    {
        $labels = [];

        foreach($dataset->getTuples() as $index => $tuple) {
            $labels[$tuple[0]][] = $index;
        }
        $labelKeys = array_keys($labels);

        $labelCount = count($labels);
        for($j = 0; $j < $labelCount; $j++) {
            $label = $labelKeys[$j];
            $labelSize = count($labels[$label]);

            $testKeys = [];
            $testKeyCount = 0;
            $testKeyNeeded = round($labelSize * $testSize);

            $testKeysAvailable = range(0, $labelSize - 1);
            while($testKeyCount < $testKeyNeeded) {
                $key = mt_rand(0, count($testKeysAvailable) - 1);

                $testKeyCount++;
                $testKeys[] = $testKeysAvailable[$key];
                unset($testKeysAvailable[$key]);
                $testKeysAvailable = array_values($testKeysAvailable);
            }

            $trainKeys = array_diff($labels[$label], $testKeys);

            foreach($testKeys as $key) {
                $this->testTuples[] = $key;
            }
            foreach($trainKeys as $key) {
                $this->trainTuples[] = $key;
            }
        }
    }
}
