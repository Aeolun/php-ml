<?php

declare(strict_types=1);

namespace Phpml\CrossValidation;

use Phpml\Dataset\Dataset;

class RandomSplit extends Split
{
    /**
     * @param Dataset $dataset
     * @param float   $testSize
     */
    protected function splitDataset(Dataset $dataset, float $testSize)
    {
        $datasetSize = $dataset->getCount();

        $testKeys = [];
        $testKeyCount = 0;
        $testKeyNeeded = ceil($datasetSize * $testSize);

        $testKeysAvailable = range(0, $datasetSize - 1);


        while($testKeyCount < $testKeyNeeded) {
            $key = mt_rand(0, count($testKeysAvailable) - 1);


            $testKeyCount++;
            $testKeys[] = $testKeysAvailable[$key];

            unset($testKeysAvailable[$key]);
            $testKeysAvailable = array_values($testKeysAvailable);
        }

        $trainKeys = array_diff(range(0, $datasetSize - 1), $testKeys);

        foreach($testKeys as $key) {
            $this->testTuples[$key] = true;
        }
        foreach($trainKeys as $key) {
            $this->trainTuples[$key] = true;
        }
    }
}
