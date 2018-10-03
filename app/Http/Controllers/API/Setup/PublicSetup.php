<?php
/**
 * Created by PhpStorm.
 * User: timpasternak
 * Date: 28.07.18
 * Time: 13:38
 */

namespace App\Http\Controllers\API\Setup;


use App\Components\API\APIResponseBuilder;
use App\Components\Core\ActionLog\ActionLog;
use App\Components\Setup\Setup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicSetup
{
    public function postStartSetup( Request $request) {
        $setup = new Setup();
        $doSetup = true;
        $result = '';


        if(file_exists(storage_path("app/installer.lock"))) {
            ActionLog::addLog("Tried to restart setup, lockfile present. Aborting...", 0);

            $setup->status = false;
            $setup->response['errors'][] = "Found Lockfile for setup. Aborting...";
            $doSetup = false;

            $result = $setup->response;
        }
        ActionLog::addLog("Setup started", 0);

        if($doSetup) {
            $result = $setup->startSetup($request);

        }



        $response = new APIResponseBuilder();
        $ActionResponse = json_encode($result,true);

        if($setup->status) {

            ActionLog::addLog("Writing lockfile.",0);

            Storage::disk('local')->put('installer.lock', '');

            ActionLog::addLog("Setup finished. Response: ".$ActionResponse, 0);

            return $response->makePositiveResponse($result)->makeResponse();
        }
        else {
            ActionLog::addLog("Setup aborted. Response: ". $ActionResponse, 0);


            return $response->makeNegativeResponse($result)->makeResponse();

        }

    }
}