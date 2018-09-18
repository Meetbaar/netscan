<?php

namespace App\Jobs;

use App\IPAdress;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ReenterScanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ipsToReindex = IPAdress::where('status', 'up')->orWhere('lastFound', ">", time()-360)->get();
        foreach($ipsToReindex as $ip) {
            $queueOffset = rand(0,1);

            dispatch((new IPScanJob($ip))->onQueue(2+$queueOffset));

        }

        self::dispatch()->onQueue(2)->delay(now()->addMinutes(30));
    }
}
