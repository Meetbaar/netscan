<?php
/**
 * Created by PhpStorm.
 * User: timpasternak
 * Date: 27.07.18
 * Time: 14:05
 */

namespace App\Http\Controllers\API\Subnets;


use App\Components\API\APIResponseBuilder;
use App\Components\UserManagement\User\UserModel;
use App\Http\Controllers\Controller;
use App\Subnet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrivateSubnets extends Controller
{
    public function getSubnets() {

        $subnetList = DB::table("ip_subnets as s")->select(DB::raw("s.*, (SELECT username FROM users AS u WHERE u.uid = s.creator) AS creator_name"))->get();
        $api = new APIResponseBuilder();
        return $api->makePositiveResponse($subnetList)->makeResponse();
    }
}