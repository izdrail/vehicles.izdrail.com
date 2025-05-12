<?php

namespace App\Service;

# Increase  memory limit without chainging the php.ini
ini_set('memory_limit', -1);

use Symfony\Component\HttpFoundation\File\Exception\FileException;

class CsvReader
{
    public function read(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new FileException("CSV file not found: $filePath");
        }

        // file() returns an array of lines or false on failure.
        // For an empty file, it returns [].
        $lines = file($filePath);

        if ($lines === false) {
             // This case should ideally not happen if file_exists is true,
             // but included for robustness.
             throw new \RuntimeException("Could not read CSV file: " . $filePath);
        }

        // Process lines into CSV rows
        $csv = array_map('str_getcsv', $lines);

        // *** FIX: Check if $csv is empty after mapping (e.g., truly empty file or file with only empty lines) ***
        if (empty($csv)) {
            return [];
        }
        // **********************************************************************************************************

        // Now it's safe to call array_shift because $csv is not empty
        $headers = array_map('trim', array_shift($csv));

        $data = [];

        foreach ($csv as $row) {
            // Ensure $row is an array and has the correct number of columns
            if (!is_array($row) || count($row) !== count($headers)) {
                continue;
            }
            // array_combine requires that the number of elements for keys and values is the same.
            // The previous check helps ensure this under normal circumstances with str_getcsv.
            $data[] = array_combine($headers, array_map('trim', $row));
        }

        return $data;
    }
}
