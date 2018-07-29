<?php
/**
 * Created by PhpStorm.
 * User: timpasternak
 * Date: 28.07.18
 * Time: 11:47
 */

namespace App\Components\Core\ActionLog;


use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    private $Log;

    public static function logAction($description, $uid) {
        $ActionLog = new Action();
        $ActionLog->action = $description;
        $ActionLog->uid = $uid;
        $ActionLog->save();

        return $ActionLog;
    }

    public function getActionLog($uid = 0) {

        $specificUser = true;

        if($uid == 0) {
            $specificUser = false;
        }

        $logItems = [];

        if($specificUser) {
            $this->Log = self::where("uid", $uid)->get();
        } else {
            $this->Log = self::get();
        }
        return $this->Log;
    }
}