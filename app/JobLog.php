<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class JobLog extends Model
{
    protected $table = "job_log";

    static function addJobLog($job_id, $title) {
        $jobLog = new self();
        $jobLog->job_id = $job_id;
        $jobLog->progress = 0;
        $jobLog->log=$title;
        $jobLog->status = "preparing";
        $jobLog->save();
    }

    static function startJob($job_id) {
        $job = self::where("job_id", $job_id)->first();
        $job->status = "running";
        $job->save();

    }
    static function endJob($job_id) {
        $job = self::where("job_id", $job_id)->first();
        $job->status = "done";
        $job->progress = "100";
        $job->save();

    }

    static function setProgress($job_id, $progress) {
        $job = self::where("job_id", $job_id)->first();
        $job->progress = $progress;
        $job->save();
    }

    static function getRunningJobs($minutes = 60, $limit = 100)
    {
        $results = self::where('status', "running")->orderBy('updated_at', 'desc')->take($limit)->get();
        return $results;
    }
    static function getDoneJobs($minutes = 60, $limit = 100)
    {
        $results = self::where('status', "done")->where("updated_at", ">", Carbon::now()->subMinutes(intval($minutes))->toDateTimeString())->orderBy('updated_at', 'desc')->take($limit)->get();
        return $results;
    }
    static function getScheduledJobs($minutes = 60, $limit = 100)
    {
        $results = self::where('status', "scheduled")->where("updated_at", ">", Carbon::now()->subMinutes(intval($minutes))->toDateTimeString())->orderBy('updated_at', 'desc')->take($limit)->get();
        return $results;
    }
    static function getErrorJobs($minutes = 60, $limit = 100)
    {
        $results = self::where('status', "error")->where("updated_at", ">", Carbon::now()->subMinutes(intval($minutes))->toDateTimeString())->orderBy('updated_at', 'desc')->take($limit)->get();
        return $results;
    }

    static function getAllJobs($minutes = 60) {
        $returnJobs = [];
        $returnJobs[] = self::getErrorJobs($minutes,2);
        $returnJobs[] = self::getRunningJobs($minutes,5);
        $returnJobs[] = self::getScheduledJobs($minutes,5);
        $returnJobs[] = self::getDoneJobs($minutes,3);
        return $returnJobs;
    }

    static function getJobsWithStatus($status, $minutes = 60) {
        $results = self::where('status', $status)->where("updated_at", ">=", Carbon::now()->subMinutes($minutes)->toDateTimeString())->get();
        return $results;

    }


}
