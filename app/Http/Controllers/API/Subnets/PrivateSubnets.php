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
use App\IPAdress;
use App\Jobs\SubnetCreationJob;
use App\Subnet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class PrivateSubnets extends Controller
{
    public function getSubnets() {

        $subnetList = DB::table("ip_subnets as s")->select(DB::raw("s.*, (SELECT username FROM users AS u WHERE u.uid = s.creator) AS creator_name"))->get();
        $api = new APIResponseBuilder();
        return $api->makePositiveResponse($subnetList)->makeResponse();
    }
    public function getSubnet($id) {

        $subnetList = DB::table("ip_subnets as s")->select(DB::raw("s.*, (SELECT username FROM users AS u WHERE u.uid = s.creator) AS creator_name"))->where('id',$id)->first();
        $ipCount = IPAdress::where('subnet', $id)->where(function ($query) {
            $query->where('status', "up")
                ->orWhere('lastFound', '>', time()-3600);
        });
        $subnetList->countedIPs = $ipCount->count();
        $api = new APIResponseBuilder();
        return $api->makePositiveResponse($subnetList)->makeResponse();
    }

    public function createSubnet(Request $request) {
        $input = Input::all();
        if(!$input['name'] || !$input['subnet']) abort(400);
        $subnet = new Subnet();
        $subnet->name = $input['name'];
        $subnet->subnet = $input['subnet'];
        $subnet->creator = $request->user()->uid;
        $subnet->save();

        $job_id = dispatch((new SubnetCreationJob($subnet))->onQueue("2"));

        $response = new APIResponseBuilder();
        $response->makePositiveResponse($subnet)->makeResponse();
    }

}