<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IPAdress extends Model
{
    protected $table = "ip_adresses";

    static function updateIP($id, $ports, $status,$hostname) {
        $ip = self::where('id', $id)->first();
        $ip->status = $status;
        $ip->hostname = $hostname;
        $ip->open_ports = json_encode($ports, true);
        if($status == "up") {
            $ip->lastFound = time();
        }
        $ip->save();

    }

    static function interpretPort($text) {
        json_decode($text, true);
        $portCollection = [];
        foreach($text as $port) {
            $portDetails = explode("/",$port);
            $portCollection[] = [
                'app'=>$portDetails[0],
                'port'=>$portDetails[1],
                'type'=>$portDetails[2],
            ];
        }
        return $portCollection;
    }
}
