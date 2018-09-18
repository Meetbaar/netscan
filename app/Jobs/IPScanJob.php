<?php

namespace App\Jobs;

use App\IPAdress;
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
            $ip = $this->ip->adress;
            $nmap = new \Nmap\Nmap();
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


            \App\IPAdress::updateIP($this->ip->id, $data['ports'], $data['status']);
        }
        catch(\Exception $exception) {
            echo $exception;
        }
    }
}
