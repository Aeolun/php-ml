<?php

declare(strict_types=1);

namespace tests\Phpml\CrossValidation;

use Phpml\CrossValidation\StratifiedRandomSplit;
use Phpml\Dataset\ArrayDataset;

class StratifiedRandomSplitTest extends \PHPUnit_Framework_TestCase
{
    public function testDatasetStratifiedRandomSplitWithEvenDistribution()
    {
        $dataset = new ArrayDataset(
            $samples = [[1], [2], [3], [4], [5], [6], [7], [8]],
            $labels = ['a', 'a', 'a', 'a', 'b', 'b', 'b', 'b']
        );

        $split = new StratifiedRandomSplit($dataset, 0.5);

        $this->assertEquals(2, $this->countSamplesByTarget($split->getTestTuples(), 'a'));
        $this->assertEquals(2, $this->countSamplesByTarget($split->getTestTuples(), 'b'));
    }

    public function testDatasetStratifiedRandomSplitWithEvenDistributionAndNumericTargets()
    {
        $dataset = new ArrayDataset(
            $samples = [[1], [2], [3], [4], [5], [6], [7], [8]],
            $labels = [1, 2, 1, 2, 1, 2, 1, 2]
        );

        $split = new StratifiedRandomSplit($dataset, 0.5);

        $this->assertEquals(2, $this->countSamplesByTarget($split->getTestTuples(), 1));
        $this->assertEquals(2, $this->countSamplesByTarget($split->getTestTuples(), 2));

        $split = new StratifiedRandomSplit($dataset, 0.25);

        $this->assertEquals(1, $this->countSamplesByTarget($split->getTestTuples(), 1));
        $this->assertEquals(1, $this->countSamplesByTarget($split->getTestTuples(), 2));
    }

    /**
     * @param $splitTargets
     * @param $countTarget
     *
     * @return int
     */
    private function countSamplesByTarget($splitTargets, $countTarget): int
    {
        $count = 0;
        foreach ($splitTargets as $target) {
            if ($target[0] === $countTarget) {
                ++$count;
            }
        }

        return $count;
    }
}
