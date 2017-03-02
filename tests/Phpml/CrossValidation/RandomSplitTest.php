<?php

declare(strict_types=1);

namespace tests\Phpml\CrossValidation;

use Phpml\CrossValidation\RandomSplit;
use Phpml\Dataset\ArrayDataset;

class RandomSplitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Phpml\Exception\InvalidArgumentException
     */
    public function testThrowExceptionOnToSmallTestSize()
    {
        new RandomSplit(new ArrayDataset([], []), 0);
    }

    /**
     * @expectedException \Phpml\Exception\InvalidArgumentException
     */
    public function testThrowExceptionOnToBigTestSize()
    {
        new RandomSplit(new ArrayDataset([], []), 1);
    }

    public function testDatasetRandomSplitWithoutSeed()
    {
        $dataset = new ArrayDataset(
            $samples = [[1], [2], [3], [4]],
            $labels = ['a', 'a', 'b', 'b']
        );

        $randomSplit = new RandomSplit($dataset, 0.5);

        $this->assertCount(2, iterator_to_array($randomSplit->getTestTuples()));
        $this->assertCount(2, iterator_to_array($randomSplit->getTrainTuples()));

        $randomSplit2 = new RandomSplit($dataset, 0.25);

        $this->assertCount(1, iterator_to_array($randomSplit2->getTestTuples()));
        $this->assertCount(3, iterator_to_array($randomSplit2->getTrainTuples()));
    }

    public function testDatasetRandomSplitWithSameSeed()
    {
        $dataset = new ArrayDataset(
            $samples = [[1], [2], [3], [4], [5], [6], [7], [8]],
            $labels = ['a', 'a', 'a', 'a', 'b', 'b', 'b', 'b']
        );

        $seed = 123;

        $randomSplit1 = new RandomSplit($dataset, 0.5, $seed);
        $randomSplit2 = new RandomSplit($dataset, 0.5, $seed);

        $this->assertEquals($randomSplit1->getTestTuples(), $randomSplit2->getTestTuples());
        $this->assertEquals($randomSplit1->getTrainTuples(), $randomSplit2->getTrainTuples());
    }

    public function testDatasetRandomSplitWithDifferentSeed()
    {
        $dataset = new ArrayDataset(
            $samples = [[1], [2], [3], [4], [5], [6], [7], [8]],
            $labels = ['a', 'a', 'a', 'a', 'b', 'b', 'b', 'b']
        );

        $randomSplit1 = new RandomSplit($dataset, 0.5, 4321);
        $testTuples = iterator_to_array($randomSplit1->getTestTuples());
        $trainTuples = iterator_to_array($randomSplit1->getTrainTuples());

        $randomSplit2 = new RandomSplit($dataset, 0.5, 1234);
        $testTuples2 = iterator_to_array($randomSplit2->getTestTuples());
        $trainTuples2 = iterator_to_array($randomSplit2->getTrainTuples());

        $this->assertNotEquals($testTuples, $testTuples2);
        $this->assertNotEquals($trainTuples, $trainTuples2);
    }

    public function testRandomSplitCorrectSampleAndLabelPosition()
    {
        $dataset = new ArrayDataset(
            $samples = [[1], [2], [3], [4]],
            $labels = [1, 2, 3, 4]
        );

        $randomSplit = new RandomSplit($dataset, 0.5);

        $this->assertEquals(iterator_to_array($randomSplit->getTestTuples())[0][0], iterator_to_array($randomSplit->getTestTuples())[0][1][0]);
        $this->assertEquals(iterator_to_array($randomSplit->getTrainTuples())[1][0], iterator_to_array($randomSplit->getTrainTuples())[1][1][0]);
    }
}
