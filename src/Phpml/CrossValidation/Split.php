<?php

declare(strict_types=1);

namespace Phpml\CrossValidation;

use Phpml\Dataset\Dataset;
use Phpml\Exception\InvalidArgumentException;

abstract class Split
{
    protected $originalSet = null;

    /**
     * @var array
     */
    protected $trainTuples = [];

    /**
     * @var array
     */
    protected $testTuples = [];

    /**
     * @param Dataset $dataset
     * @param float   $testSize
     * @param int     $seed
     *
     * @throws InvalidArgumentException
     */
    public function __construct(Dataset $dataset, float $testSize = 0.3, int $seed = null)
    {
        if (0 >= $testSize || 1 <= $testSize) {
            throw InvalidArgumentException::percentNotInRange('testSize');
        }

        $this->originalSet = $dataset;

        $this->seedGenerator($seed);

        $this->splitDataset($dataset, $testSize);
    }

    abstract protected function splitDataset(Dataset $dataset, float $testSize);

    /**
     * @return array
     */
    public function getTrainTuples() : \Generator
    {
        foreach($this->originalSet->getTuples() as $index => $tuple) {
            if (isset($this->trainTuples[$index])) {
                yield $tuple;
            }
        }
    }

    /**
     * @return array
     */
    public function getTestTuples() : \Generator
    {
        foreach($this->originalSet->getTuples() as $index => $tuple) {
            if (isset($this->testTuples[$index])) {
                yield $tuple;
            }
        }
    }

    public function getTrainIndexes() {
        return $this->trainTuples;
    }

    public function getTestIndexes() {
        return $this->testTuples;
    }

    /**
     * @param int|null $seed
     */
    protected function seedGenerator(int $seed = null)
    {
        if (null === $seed) {
            mt_srand();
        } else {
            mt_srand($seed);
        }
    }
}
