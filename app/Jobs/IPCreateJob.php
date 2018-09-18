<?php

namespace App\Jobs;

use App\IPAdress;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class IPCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $address;
    private $subnet;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($address, $subnet)
    {
        $this->address = $address;
        $this->subnet = $subnet;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ipAdress = new IPAdress();
        $ipAdress->adress = $this->address;
        $ipAdress->subnet = $this->subnet;
        $ipAdress->open_ports = json_encode([], true);
        try {
            $ipAdress->save();
        }
        catch(\Exception $exception)
        {
            echo $exception;
        }
        $offset = rand(0,1);
        dispatch((new IPScanJob($ipAdress))->onQueue(4+$offset));

    }
}
