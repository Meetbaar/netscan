<?php
/**
 * Created by PhpStorm.
 * User: timpasternak
 * Date: 28.07.18
 * Time: 11:53
 */

namespace App\Http\Controllers\API\Dashboard;


use App\Components\API\APIResponseBuilder;
use App\Components\Core\ActionLog\Action;
use App\Components\Core\ActionLog\ActionLog;
use App\Http\Controllers\Controller;
use App\JobLog;
use App\Jobs;

class PrivateDashboard extends Controller
{

    public function getJobList()
    {

        $jobList = JobLog::getAllJobs();
        $array = [];
        for($i=0; $i<count($jobList);$i++) {
            foreach($jobList[$i] as $item) {
                array_push($array,$item);

            }
        }
        $api = new APIResponseBuilder();
        return $api->makePositiveResponse($array)->makeResponse();
    }

    public function getQueueLoad() {
        $result = [];
        $queues = [1,2,3,4,5];
        foreach($queues as $queue) {
            $result[] = ["id"=>$queue, "load"=>Jobs::getQueueLoad($queue)];
        }
        $api = new APIResponseBuilder();
        return $api->makePositiveResponse($result)->makeResponse();

    }
}