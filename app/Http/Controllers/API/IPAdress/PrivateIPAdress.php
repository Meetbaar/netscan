<?php
/**
 * Created by PhpStorm.
 * User: timpasternak
 * Date: 27.07.18
 * Time: 14:05
 */

namespace App\Http\Controllers\API\IPAdress;


use App\Components\API\APIResponseBuilder;
use App\Components\UserManagement\User\UserModel;
use App\Http\Controllers\Controller;
use App\IPAdress;
use App\Subnet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrivateIPAdress extends Controller
{
    public function getLatestChangedIPs() {

        $ipList = DB::table("ip_adresses AS a")->select(DB::raw("a.*, (SELECT name FROM ip_subnets AS s WHERE s.id = a.subnet) AS subnet_name"))->orderBy('updated_at', 'desc')->take(10)->get();

        $api = new APIResponseBuilder();
        return $api->makePositiveResponse($ipList)->makeResponse();
    }
}