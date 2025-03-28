<?php
require_once 'vendor/autoload.php';

use taskforce\converter\CsvSqlConverter;
use taskforce\exceptions\ConverterException;
use taskforce\exceptions\StatusActionException;
use taskforce\logic\actions\ResponseAction;
use taskforce\logic\AvailableActions;

try {
    $converter = new CsvSqlConverter('data\csv');
    $converter->convertFiles('data\sql');
} catch (ConverterException $e) {
    exit($e->getMessage());
}

try {
    $strategy = new AvailableActions(AvailableActions::STATUS_NEW, 1, 3);
    $nextStatus = $strategy->getNextStatus(ResponseAction::class);
    var_dump('next status new -> Response', $nextStatus);
    print(PHP_EOL);
} catch (StatusActionException $e) {
    exit($e->getMessage());
}

var_dump('new -> performer', $strategy->getAvailableActions(AvailableActions::ROLE_PERFORMER, 2));
var_dump('new -> client,alien', $strategy->getAvailableActions(AvailableActions::ROLE_CLIENT, 2));
var_dump('new -> client,same', $strategy->getAvailableActions(AvailableActions::ROLE_CLIENT, 1));
$strategy = new AvailableActions(AvailableActions::STATUS_IN_PROGRESS, 1, 3);
var_dump('proceed -> performer,same', $strategy->getAvailableActions(AvailableActions::ROLE_PERFORMER, 3));
