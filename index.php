<?php
require_once 'vendor/autoload.php';

use taskforce\logic\actions\ResponseAction;
use taskforce\logic\AvailableActions;

$strategy = new AvailableActions(AvailableActions::STATUS_NEW, 1, 3);
$nextStatus = $strategy->getNextStatus(ResponseAction::class);
var_dump('next status new -> Response', $nextStatus); print(PHP_EOL);

var_dump('new -> performer', $strategy->getAvailableActions(AvailableActions::ROLE_PERFORMER, 2));
var_dump('new -> client,alien', $strategy->getAvailableActions(AvailableActions::ROLE_CLIENT, 2));
var_dump('new -> client,same', $strategy->getAvailableActions(AvailableActions::ROLE_CLIENT, 1));
$strategy = new AvailableActions(AvailableActions::STATUS_IN_PROGRESS, 1, 3);
var_dump('proceed -> performer,same', $strategy->getAvailableActions(AvailableActions::ROLE_PERFORMER, 3));
