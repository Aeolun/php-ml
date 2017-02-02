<?php

declare(strict_types=1);

namespace Phpml\Dataset;

use Phpml\Exception\DatasetException;

class CsvDataset implements Dataset
{
    protected $filePath;
    protected $headingRow;

    /**
     * @param string $filepath
     * @param int    $features
     * @param bool   $headingRow
     *
     * @throws DatasetException
     */
    public function __construct(string $filepath, bool $headingRow = true)
    {
        if (!file_exists($filepath)) {
            throw DatasetException::missingFile(basename($filepath));
        }

        $this->filePath = $filepath;
        $this->headingRow = $headingRow;
    }

    /**
     * @return array
     */
    public function getTuples(): \Generator
    {
        if (false === $handle = fopen($this->filePath, 'rb')) {
            throw DatasetException::cantOpenFile(basename($this->filePath));
        }

        if ($this->headingRow) {
            fgets($handle);
        }

        $features = null;
        while (($data = fgetcsv($handle, 0, ',')) !== false) {
            if ($features == null) {
                $features = count($data)-1;
            }
            yield [
                $data[$features],
                array_slice($data, 0, $features)
            ];

        }
        fclose($handle);
    }

    public function getCount(): int
    {
        $lineCount = 0;

        if (false === $handle = fopen($this->filePath, 'rb')) {
            throw DatasetException::cantOpenFile(basename($this->filePath));
        }

        while(!feof($handle)){
            fgets($handle);

            $lineCount++;
        }

        return $lineCount;
    }
}
