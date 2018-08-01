<?php
/**
 * Created by PhpStorm.
 * User: timpasternak
 * Date: 29.07.18
 * Time: 12:02
 */

namespace App\Components\UserManagement\User;


use App\Components\API\APIResponseBuilder;
use App\Components\Core\ActionLog\ActionLog;
use Illuminate\Support\Facades\Auth;

class UserAction
{
    public $actionStatus = true;

    public function UserLogin($username, $password) {
        $credentials = [
            'username'=>$username,
            'password'=>$password,
        ];

        $api = new APIResponseBuilder();

        if(Auth::attempt($credentials)) {

            ActionLog::addLog("Userlogin succeeded for: $username", 0);


            return "Authentication successful";
        } else {

            ActionLog::addLog("Userlogin failed for: $username", 0);
            $this->actionStatus = false;
            return ["message"=>"Username or Password wrong."];
        }
    }
}