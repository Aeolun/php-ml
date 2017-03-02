<?php

declare(strict_types=1);

namespace Phpml\SupportVectorMachine;

class DataTransformer
{
    /**
     * @param array $samples
     * @param array $labels
     * @param bool  $targets
     *
     * @return string
     */
    public static function outputTrainingSet($outputFile, $tuples, $numericLabels = false)
    {
        file_put_contents($outputFile, '');

        $numeric = [];
        foreach ($tuples as $data) {
            $label = $data[0];
            if ( ! $numericLabels) {
                $label = self::numericLabel($label, $numeric);
            }

            file_put_contents($outputFile, sprintf('%s %s %s', $label, self::sampleRow($data[1]), PHP_EOL), FILE_APPEND);
        }

        return $numeric;
    }

    /**
     * @param array $samples
     *
     * @return string
     */
    public static function outputTestSet($outputFile, array $samples)
    {
        file_put_contents($outputFile, '');

        foreach ($samples as $data) {
            $label = 0;

            file_put_contents($outputFile, sprintf('%s %s %s', $label, self::sampleRow($data), PHP_EOL), FILE_APPEND);
        }
    }

    /**
     * @param string $rawPredictions
     * @param array  $labels
     *
     * @return array
     */
    public static function predictions(string $rawPredictions, array $labels): array
    {
        $results = [];
        foreach (explode(PHP_EOL, $rawPredictions) as $result) {
            if (strlen($result) > 0) {
                $results[] = array_search($result, $labels);
            }
        }

        return $results;
    }

    /**
     * @param array $labels
     *
     * @return array
     */
    public static function numericLabel($label, array &$labels): int
    {
        if (isset($labels[$label])) {
            return $labels[$label];
        }

        $labels[$label] = count($labels);

        return $labels[$label];
    }

    /**
     * @param array $sample
     *
     * @return string
     */
    private static function sampleRow(array $sample): string
    {
        $row = [];
        foreach ($sample as $index => $feature) {
            $row[] = sprintf('%s:%s', $index + 1, $feature);
        }

        return implode(' ', $row);
    }
}
