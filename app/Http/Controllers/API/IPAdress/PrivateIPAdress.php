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
use Illuminate\Support\Facades\Input;

class PrivateIPAdress extends Controller
{
    static $filter;
    public function getLatestChangedIPs() {

        $ipList = DB::table("ip_adresses AS a")->select(DB::raw("a.*, (SELECT name FROM ip_subnets AS s WHERE s.id = a.subnet) AS subnet_name"))->orderBy('updated_at', 'desc')->take(5)->get();

        $api = new APIResponseBuilder();
        return $api->makePositiveResponse($ipList)->makeResponse();
    }

    public function getIPs($subnet, $filter = "") {
        $resultSet = [];
        $input = Input::all();
        $limit = 100;
        if(!empty($input['limit'])) {
            $limit = $input['limit'];
        }
        $baseQuery = IPAdress::where("subnet", $subnet);

        if($filter != "") {
            PrivateIPAdress::$filter = $filter;
            $baseQuery->where(function ($query) {
                $query->where('adress', 'like', "%".PrivateIPAdress::$filter."%")
                    ->orWhere('hostname', 'like', "%".PrivateIPAdress::$filter."%")
                    ->orWhere('status', 'like', "%".PrivateIPAdress::$filter."%")
                    ->orWhere('open_ports', 'like', "%".PrivateIPAdress::$filter."%");
            });


        } else {

            if(!empty($input['status'])) {
                $status = $input['status'];
            } else {
                $status = "";
            }

            if($status === "online") {
                $baseQuery->where('status', "up")->orWhere('lastFound', ">", time()-3600);
            } else if($status === "offline") {
                $baseQuery->whereIn("status", ['created', 'down', 'error']);
            }


        }

        if($filter != "") {
            $baseQuery->orderBy("status",'asc');
        } else {
            $baseQuery->orderBy('id', 'desc');

        }
        $resultSet = $baseQuery->where("subnet", $subnet)->take($limit)->get();
        foreach($resultSet as $adress) {
            $adress->open_ports = json_decode($adress->open_ports, true);
            $portCollection = [];
            $domain = explode('.',$adress->hostname);
            $shortAdress =$domain[0];

            foreach($adress->open_ports as $port) {
                $portDetails = explode("/",$port);
                $portCollection[] = [
                    'app'=>$portDetails[0],
                    'port'=>$portDetails[1],
                    'type'=>$portDetails[2],
                ];
            }
            $adress->open_ports = $portCollection;
            $adress->shortDomain = $shortAdress;
        }
        $api = new APIResponseBuilder();
        return $api->makePositiveResponse($resultSet)->makeResponse();

    }
}