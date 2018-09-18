<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IPAdress extends Model
{
    protected $table = "ip_adresses";

    static function updateIP($id, $ports, $status) {
        $ip = self::where('id', $id)->first();
        $ip->status = $status;
        $ip->open_ports = json_encode($ports, true);
        if($status == "up") {
            $ip->lastFound = time();
        }
        $ip->save();

    }
}
