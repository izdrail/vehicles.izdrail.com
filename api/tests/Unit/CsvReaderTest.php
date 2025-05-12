<?php


namespace App\Tests\Unit;

use App\Service\CsvReader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class CsvReaderTest extends TestCase
{
    private string $tempDir;

    protected function setUp(): void
    {
        $this->tempDir = sys_get_temp_dir() . '/csv_reader_test_' . uniqid();
        if (!mkdir($this->tempDir) && !is_dir($this->tempDir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $this->tempDir));
        }
    }

    protected function tearDown(): void
    {
        if (is_dir($this->tempDir)) {
            $this->removeDirectory($this->tempDir);
        }
    }

    private function createCsvFile(string $filename, string $content): string
    {
        $filePath = $this->tempDir . '/' . $filename;
        file_put_contents($filePath, $content);
        return $filePath;
    }

    private function removeDirectory(string $dir): bool
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->removeDirectory("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    public function testReadValidCsv(): void
    {
        $csvContent = <<<CSV
header1,header2,header3
value1a,value1b,value1c
value2a,value2b,value2c
CSV;
        $filePath = $this->createCsvFile('valid.csv', $csvContent);

        $reader = new CsvReader();
        $data = $reader->read($filePath);

        $expected = [
            ['header1' => 'value1a', 'header2' => 'value1b', 'header3' => 'value1c'],
            ['header1' => 'value2a', 'header2' => 'value2b', 'header3' => 'value2c'],
        ];

        $this->assertEquals($expected, $data);
    }

    public function testReadCsvWithWhitespace(): void
    {
        $csvContent = <<<CSV
  header1  , header2 ,header3
 value1a ,value1b  , value1c
value2a,value2b,value2c
CSV;
        $filePath = $this->createCsvFile('whitespace.csv', $csvContent);

        $reader = new CsvReader();
        $data = $reader->read($filePath);

        $expected = [
            ['header1' => 'value1a', 'header2' => 'value1b', 'header3' => 'value1c'],
            ['header1' => 'value2a', 'header2' => 'value2b', 'header3' => 'value2c'],
        ];

        $this->assertEquals($expected, $data);
    }

    public function testReadEmptyCsv(): void
    {
        $csvContent = "";
        $filePath = $this->createCsvFile('empty.csv', $csvContent);

        $reader = new CsvReader();
        $data = $reader->read($filePath);

        $this->assertEmpty($data);
    }

    public function testReadCsvWithOnlyHeaders(): void
    {
        $csvContent = "header1,header2,header3\n";
        $filePath = $this->createCsvFile('only_headers.csv', $csvContent);

        $reader = new CsvReader();
        $data = $reader->read($filePath);

        $this->assertEmpty($data);
    }


    public function testReadCsvWithMissingFile(): void
    {
        $reader = new CsvReader();
        $nonExistentFilePath = $this->tempDir . '/non_existent_file.csv';

        $this->expectException(FileException::class);
        $this->expectExceptionMessage("CSV file not found: " . $nonExistentFilePath);

        $reader->read($nonExistentFilePath);
    }

    public function testReadCsvWithIncorrectColumnCount(): void
    {
        $csvContent = <<<CSV
header1,header2,header3
value1a,value1b,value1c
value2a,value2b
value3a,value3b,value3c,value3d
value4a,value4b,value4c
CSV;
        $filePath = $this->createCsvFile('incorrect_count.csv', $csvContent);

        $reader = new CsvReader();
        $data = $reader->read($filePath);

        // Only rows with the correct column count should be included
        $expected = [
            ['header1' => 'value1a', 'header2' => 'value1b', 'header3' => 'value1c'],
            ['header1' => 'value4a', 'header2' => 'value4b', 'header3' => 'value4c'],
        ];

        $this->assertEquals($expected, $data);
    }
}
