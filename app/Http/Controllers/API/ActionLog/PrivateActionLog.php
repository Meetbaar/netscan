<?php
/**
 * Created by PhpStorm.
 * User: timpasternak
 * Date: 28.07.18
 * Time: 11:53
 */

namespace App\Http\Controllers\API\ActionLog;


use App\Components\API\APIResponseBuilder;
use App\Components\Core\ActionLog\Action;
use App\Components\Core\ActionLog\ActionLog;
use App\Http\Controllers\Controller;

class PrivateActionLog extends Controller
{

    public function getActionLog($uid = 0){

        $api  = new APIResponseBuilder();
        $ActionLog = new Action();
        return $api->makePositiveResponse($ActionLog->getActionLog($uid))->makeResponse();
    }
}