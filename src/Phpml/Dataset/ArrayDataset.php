<?php

declare(strict_types=1);

namespace Phpml\Dataset;

use Phpml\Exception\InvalidArgumentException;

class ArrayDataset implements Dataset
{
    /**
     * @var array
     */
    protected $samples = [];

    /**
     * @var array
     */
    protected $targets = [];

    /**
     * @param array $samples
     * @param array $targets
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $samples, array $targets)
    {
        if (count($samples) != count($targets)) {
            throw InvalidArgumentException::arraySizeNotMatch();
        }

        $this->samples = $samples;
        $this->targets = $targets;
    }

    public function getTuples(): \Generator
    {
        foreach($this->samples as $index => $features) {
            yield [
                $this->targets[$index],
                $features
            ];
        }
    }

    public function getCount(): int
    {
        return count($this->samples);
    }


}
