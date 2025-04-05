<?php

namespace app\converter;

use DirectoryIterator;
use exceptions\ConverterException;
use SplFileInfo;
use SplFileObject;

class CsvSqlConverter
{
    private array $filesToConvert = [];

    public function __construct(string $directory)
    {
        if (!is_dir($directory)) {
            throw new ConverterException("Directory '$directory' не существует");
        }

        $this->loadCsvFiles($directory);
    }

    public function convertFiles(string $outputDirectory): array
    {
        if (!is_dir($outputDirectory)) {
            throw new ConverterException('Директория для выходных файлов не существует');
        }

        $result = [];
        foreach ($this->filesToConvert as $file) {
            $result[] = $this->convertFile($file, $outputDirectory);
        }
        return $result;
    }

    private function loadCsvFiles(string $directory)
    {
        foreach (new DirectoryIterator($directory) as $fileInfo) {
            if ($fileInfo->getExtension() == "csv") {
                $this->filesToConvert[] = $fileInfo->getFileInfo();
            }
        }
    }

    private function convertFile(SplFileInfo $file, string $outputDirectory): string
    {
        $fileObject = new SplFileObject($file->getPathname());
        $fileObject->setFlags(SplFileObject::READ_CSV);

        $columns = $fileObject->fgetcsv();
        array_walk($columns, function (&$value) {
            $value = "`$value`";
        });
        $values = [];

        while (!$fileObject->eof()) {
            $csv = $fileObject->fgetcsv();
            if (!$csv) continue;
            $values[] = $csv;
        }

        $tableName = $file->getBasename('.csv');
        $sqlContent = $this->getSqlContent($tableName, $columns, $values);

        return $this->saveSqlContent($tableName, $sqlContent, $outputDirectory);
    }

    private function getSqlContent(string $tableName, array $columns, array $values): string
    {
        $sql = "INSERT INTO $tableName (" . implode(', ', $columns) . ") VALUES";
        foreach ($values as $row) {
            array_walk($row, function (&$value) {
                $value = addslashes($value);
                $value = "'$value'";
            });

            $sql .= PHP_EOL . "    (" . implode(', ', $row) . "),";
        }

        $sql = substr($sql, 0, -1);
        return $sql . ';';
    }

    private function saveSqlContent(string $tableName, $sqlContent, string $outputDirectory): string
    {
        if (!is_dir($outputDirectory)) {
            throw new ConverterException('Директория для выходных файлов не существует');
        }

        $filepath = $outputDirectory . DIRECTORY_SEPARATOR . $tableName . '.sql';
        file_put_contents($filepath, $sqlContent);
        return $filepath;
    }
}