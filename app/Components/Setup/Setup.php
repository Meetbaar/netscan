<?php
/**
 * Created by PhpStorm.
 * User: timpasternak
 * Date: 28.07.18
 * Time: 13:38
 */

namespace App\Components\Setup;


use App\Components\Core\ActionLog\ActionLog;
use App\Components\UserManagement\User\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Setup
{
    public $status = true;
    public $response = [];

    public function startSetup( Request $request) {
        //Create User

        $validatedData = Validator::make($request->all(),[
            'username' => 'required|unique:users|max:255',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8',
        ]);
        ActionLog::addLog("Starting Validation", 0);

        if($validatedData->fails()) {
            $this->status = false;
            ActionLog::addLog("Validation failed", 0);

            $this->response['errors'] = $validatedData->errors();

        } else {
            ActionLog::addLog("Validation succeeded.", 0);

            $this->response['user'] = SetupTasks::createUser($request->input("username"), $request->input("password"), $request->input("email"));
        }

        return $this->response;
    }

}