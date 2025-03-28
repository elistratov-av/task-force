<?php

use taskforce\converter\CsvSqlConverter;
use taskforce\exceptions\ConverterException;

require_once 'vendor/autoload.php';

try {
    $converter = new CsvSqlConverter('data/csv');
    $result = $converter->convertFiles('data/sql');
} catch (ConverterException $e) {
    exit($e->getMessage());
}

var_dump($result);