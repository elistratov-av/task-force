<?php

namespace app\logic\actions;

class StartAction extends AbstractAction
{
    public static function getLabel()
    {
        return "Принять";
    }

    public static function getInternalName()
    {
        return 'act_start';
    }

    public static function checkRights($userId, $performerId, $clientId)
    {
        return $userId == $clientId;
    }
}