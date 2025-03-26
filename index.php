<?php
require_once 'vendor/autoload.php';

use taskforce\logic\AvailableActions;

$strategy = new AvailableActions(AvailableActions::STATUS_NEW, 1);
print_r($strategy->getActionsMap());
