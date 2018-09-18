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

    static function getRunningJobs($minutes = 60)
    {
        $results = self::where('status', "running")->whereDate("updated_at", ">=", Carbon::now()->subMinutes($minutes))->get();
        return $results;
    }
    static function getDoneJobs($minutes = 60)
    {
        $results = self::where('status', "done")->whereDate("updated_at", ">=", Carbon::now()->subMinutes($minutes))->get();
        return $results;
    }
    static function getScheduledJobs($minutes = 60)
    {
        $results = self::where('status', "scheduled")->whereDate("updated_at", ">=", Carbon::now()->subMinutes($minutes))->get();
        return $results;
    }
    static function getErrorJobs($minutes = 60)
    {
        $results = self::where('status', "error")->whereDate("updated_at", ">=", Carbon::now()->subMinutes($minutes))->get();
        return $results;
    }

    static function getAllJobs($minutes = 60) {
        $returnJobs = [];
        $returnJobs[] = self::getErrorJobs($minutes);
        $returnJobs[] = self::getRunningJobs($minutes);
        $returnJobs[] = self::getScheduledJobs($minutes);
        $returnJobs[] = self::getDoneJobs($minutes);
        return $returnJobs;
    }

    static function getJobsWithStatus($status, $minutes = 60) {
        $results = self::where('status', "error")->whereDate("updated_at", ">=", Carbon::now()->subMinutes($minutes))->get();
        return $results;

    }


}
