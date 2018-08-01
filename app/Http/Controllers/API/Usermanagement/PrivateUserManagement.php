<?php
/**
 * Created by PhpStorm.
 * User: timpasternak
 * Date: 27.07.18
 * Time: 14:05
 */

namespace App\Http\Controllers\API\Usermanagement;


use App\Components\API\APIResponseBuilder;
use App\Components\UserManagement\User\UserModel;
use App\Http\Controllers\Controller;

class PrivateUserManagement extends Controller
{
    public function postUserCreate() {

    }

    public function getUsers($username = ""){
        $users = [];
        $api = new APIResponseBuilder();
        if($username == "") {
            $users = UserModel::get();

        } else {
            $users = UserModel::where('username', $username)->get();
        }

        if($users != null) {
            return $api->makePositiveResponse($users)->makeResponse();
        }
        else {
            abort(404);

        }


    }
}