<?php
/**
 * Created by PhpStorm.
 * User: timpasternak
 * Date: 28.07.18
 * Time: 14:15
 */

namespace App\Components\API;


class APIResponseBuilder
{

    private $response;
    private $status;

    function makePositiveResponse($response) {
        $this->response = $response;
        $this->status = "ok";
        return $this;
    }

    function makeNegativeResponse($response) {

        $this->response = $response;
        $this->status = "error";
        return $this;
    }

    function makeResponse() {
        $skeleton = [
            "status"=> $this->status,
            "response"=> $this->response
        ];
        return $skeleton;

    }
}