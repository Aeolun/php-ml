<?php

declare(strict_types=1);

namespace tests\Phpml\Dataset;

use Phpml\Dataset\CsvDataset;

class CsvDatasetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Phpml\Exception\DatasetException
     */
    public function testThrowExceptionOnMissingFile()
    {
        new CsvDataset('missingFile', false);
    }

    public function testSampleCsvDatasetWithHeaderRow()
    {
        $filePath = dirname(__FILE__).'/Resources/dataset.csv';

        $dataset = new CsvDataset($filePath, true);

        $this->assertCount(10, $dataset->getTuples());
        $this->assertCount(10, $dataset->getTuples());
    }

    public function testSampleCsvDatasetWithoutHeaderRow()
    {
        $filePath = dirname(__FILE__).'/Resources/dataset.csv';

        $dataset = new CsvDataset($filePath, false);

        $this->assertCount(11, $dataset->getTuples());
        $this->assertCount(11, $dataset->getTuples());
    }
}
