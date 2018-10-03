<?php
/**
 * Created by PhpStorm.
 * User: timpasternak
 * Date: 28.07.18
 * Time: 15:25
 */

namespace App\Components\Setup;


use App\Components\Core\ActionLog\ActionLog;
use App\Components\UserManagement\User\UserModel;
use Illuminate\Support\Facades\Validator;

class SetupTasks extends Setup
{
    static function createUser($username, $password, $email) {
        ActionLog::addLog("Starting User creation", 0);
        $user = UserModel::createUser($username, $password, $email,0);
        return $user;
    }

}