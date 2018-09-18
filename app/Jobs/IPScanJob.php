<?php

namespace App\Jobs;

use App\IPAdress;
use App\JobLog;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class IPScanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $ip;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(IPAdress $IPAdress)
    {
        $this->ip = $IPAdress;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $job_id = $this->job->getJobId();
            JobLog::addJobLog($job_id, "Scanning ".$this->ip->adress.".");

            $ip = $this->ip->adress;
            JobLog::startJob($job_id);
            $nmap = new \Nmap\Nmap();
            JobLog::setProgress($job_id, 10);
            $data = [];
            $error = false;
            try {
                $result = $nmap->scan([ $ip ]);
            }
            catch(\Exception $exception) {
                $error = true;
            }

            $data["ip"] = $ip;
            $data["ports"] = [];
            if($error) {
                $data['status'] = "error";
            } else if (empty($result[0])) {
                $data['status'] = "down";
            } else {

                foreach($result[0]->getOpenPorts() as $port) {
                    $data["ports"][] = $port->getService()->getName()."/".$port->getNumber()."/".$port->getProtocol();
                }
                $data['status'] = $result[0]->getState();

            }
            $hostname = gethostbyaddr($ip);

            \App\IPAdress::updateIP($this->ip->id, $data['ports'], $data['status'], $hostname);
            JobLog::endJob($job_id);

        }
        catch(\Exception $exception) {
            echo $exception;
        }

    }
}
