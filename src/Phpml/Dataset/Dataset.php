<?php

declare(strict_types=1);

namespace Phpml\Dataset;

interface Dataset
{
    /**
     * @return \Generator
     */
    public function getTuples(): \Generator;

    public function getCount(): int;
}
