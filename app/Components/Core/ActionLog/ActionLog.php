<?php
/**
 * Created by PhpStorm.
 * User: timpasternak
 * Date: 28.07.18
 * Time: 11:47
 */

namespace App\Components\Core\ActionLog;


class ActionLog
{
    static function addLog($description, $uid) {
        return Action::logAction($description, $uid);
    }
}