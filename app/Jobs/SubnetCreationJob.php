<?php

namespace App\Jobs;

use App\Components\Core\ActionLog\Action;
use App\IPAdress;
use App\JobLog;
use App\Subnet;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use IPv4\SubnetCalculator;


class SubnetCreationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $subnet;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Subnet $subnet)
    {
        $this->subnet = $subnet;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $job_id = $this->job->getJobId();
        JobLog::addJobLog($job_id, "Add ".$this->subnet->subnet." to database");

        $network = explode("/", $this->subnet->subnet);
        $sub = new SubnetCalculator($network[0], $network[1]);

        $totalItems = $sub->getNumberAddressableHosts();
        $this->subnet->size = $totalItems;
        $this->subnet->start = $sub->getMinHost();
        $this->subnet->end = $sub->getMaxHost();

        $this->subnet->save();

        $minIP = ip2long($sub->getMinHost());


        /**
         *
         * We need to continue on where we left if we crashed the last time :(
         *
         */
        $pdoPreperation = IPAdress::where('subnet', $this->subnet->id);
        $countOfIPs = $pdoPreperation->count();

        if($countOfIPs > 0) {
            Action::logAction("We crashed the last time with ".$this->subnet->subnet."!",0)
            /**
             *
             * Get latest IP CREATED (not modified, it could be that the IPScanJob already ran!)
             *
             */

            $startIP = $pdoPreperation->orderBy('created_at', 'desc')->first();
            Action::logAction( "Continuing ".$this->subnet->subnet." with ".$startIP->adress.".", 0);
            $minIP = ip2long($startIP->adress);
        }
        $maxIP = ip2long($sub->getMaxHost());
        JobLog::startJob($job_id);
        for ($ip = $minIP; $ip <= $maxIP; $ip++) {
            try {
                dispatch((new IPCreateJob(long2ip($ip), $this->subnet->id))->onQueue("3"));
            } catch (\Exception $exception) {
                echo  $exception;
            }
            $currentIP = $ip-$minIP;
            $status = round(($currentIP/$totalItems)*100,0);
            JobLog::setProgress($job_id, $status);
        }

    }
}
