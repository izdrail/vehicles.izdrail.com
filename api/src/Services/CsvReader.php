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

        $csv = array_map('str_getcsv', file($filePath));
        $headers = array_map('trim', array_shift($csv));
        $data = [];

        foreach ($csv as $row) {
            if (count($row) !== count($headers)) {
                continue;
            }
            $data[] = array_combine($headers, array_map('trim', $row));
        }

        return $data;
    }
}
