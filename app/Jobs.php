<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    protected $table = "jobs";

    static function getQueueLoad($queue) {
        $load = self::where("queue", $queue)->count();
        return $load;
    }
}
