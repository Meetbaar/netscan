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
use Illuminate\Http\Request;

class PrivateUserManagement extends Controller
{
    public function postUserCreate(Request $request) {
        $user = UserModel::createUser(
            $request->input("username"),
            $request->input("password"),
            $request->input("email"),
            $request->user()->uid
        );
        return $user;
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