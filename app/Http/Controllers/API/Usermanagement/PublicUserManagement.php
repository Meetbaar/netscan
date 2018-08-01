<?php
/**
 * Created by PhpStorm.
 * User: timpasternak
 * Date: 27.07.18
 * Time: 11:36
 */

namespace App\Http\Controllers\API\Usermanagement;


use App\Components\API\APIResponseBuilder;
use App\Components\UserManagement\User\UserAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PublicUserManagement extends Controller
{

    public function postUserLogin(Request $request) {
        $rules = [
            "username" => "required",
            "password" => "required"
        ];

        $api = new APIResponseBuilder();
        $userAction = new UserAction();

        $validatorResult = Validator::make($request->all(), $rules);
        if($validatorResult->fails()) {
            return $api->makeNegativeResponse(["errors"=>$validatorResult->errors()])->makeResponse();
        } else {
            $login = $userAction->UserLogin($request->input("username"), $request->input("password"));
            if($userAction->actionStatus) {
                return $api->makePositiveResponse($login)->makeResponse();
            } else {
                return $api->makeNegativeResponse($login)->makeResponse();
            }
        }
    }

    public function getUserLogin() {

        $api = new APIResponseBuilder();

        if(Auth::check()) {
            return $api->makePositiveResponse(Auth::user())->makeResponse();
        } else {
            return $api->makeNegativeResponse("User not logged in.")->makeResponse();
        }
    }
}