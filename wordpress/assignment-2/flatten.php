<?php
namespace App;

class ArrayFlattener {
    private array $headers = [];
    private array $flattenedData = [];

    public function flatten(array $data): array {
        foreach ($data as $row) {
            $this->processRow($row, [], []);
        }
        return array_merge([$this->headers], $this->flattenedData);
    }

    private function processRow(array $row, array $staticValues, array $dynamicColumns): void {
        $staticData = [];
        $nestedData = [];

        foreach ($row as $key => $value) {
            if (is_array($value) && $this->isAssociative($value)) {
                $nestedData[$key] = $value;
            } else {
                $staticData[$key] = $value;
            }
        }

        if (empty($nestedData)) {
            $flattenedRow = array_merge($staticValues, $staticData, $dynamicColumns);
            $this->flattenedData[] = array_values($flattenedRow);
            $this->updateHeaders(array_keys($flattenedRow));
            return;
        }

        $nestedCombinations = $this->generateCombinations($nestedData);
        foreach ($nestedCombinations as $combination) {
            $this->processRow([], array_merge($staticValues, $staticData), $combination);
        }
    }

    private function generateCombinations(array $nestedData): array {
        $combinations = [[]];
        foreach ($nestedData as $prefix => $subRows) {
            $newCombinations = [];
            foreach ($combinations as $existingCombination) {
                foreach ($subRows as $subRow) {
                    $newCombinations[] = array_merge($existingCombination, $this->flattenSubRow($prefix, $subRow));
                }
            }
            $combinations = $newCombinations;
        }
        return $combinations;
    }

    private function flattenSubRow(string $prefix, array $subRow): array {
        $flattened = [];
        foreach ($subRow as $key => $value) {
            $flattened[$prefix . '-' . $key] = $value;
        }
        return $flattened;
    }

    private function updateHeaders(array $rowKeys): void {
        if (empty($this->headers)) {
            $this->headers = $rowKeys;
        }
    }

    private function isAssociative(array $arr): bool {
        return count(array_filter(array_keys($arr), 'is_string')) > 0;
    }
}

class CSVExporter {
    public static function export(array $data, string $filename = 'export.csv'): void {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . htmlspecialchars($filename, ENT_QUOTES, 'UTF-8') . '"');
        $output = fopen('php://output', 'w');
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
    }
}

// Usage example
$data = [
    'row-1' => [
        'col-1' => 'value-1', 'col-2' => 'value-2', 'col-3' => 'value-3', 'col-4' => 'value-4',
        'col-5' => [
            'col-5-row-1' => ['col-5-subcol-1' => 'col-5-subvalue-1', 'col-5-subcol-2' => 'col-5-subvalue-2'],
            'col-5-row-2' => ['col-5-subcol-1' => 'col-5-subvalue-3', 'col-5-subcol-2' => 'col-5-subvalue-4'],
        ],
        'col-6' => 'value-6'
    ],
];

$flattener = new ArrayFlattener();
$flattenedData = $flattener->flatten($data);
CSVExporter::export($flattenedData);
