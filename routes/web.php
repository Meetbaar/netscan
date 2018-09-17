<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post("/login", function(\Illuminate\Http\Request $request ){
    if(\Illuminate\Support\Facades\Auth::attempt([
        "username"=> $request->input("username"),
        "password"=> $request->input("password")
    ])) {
        return true;
    }
});

Route::get("/test/", function() {
    $nmap = new \Nmap\Nmap();

    //$result = $nmap->scan([ '10.69.11.254' ]);
    $sub = new IPv4\SubnetCalculator('10.0.0.0', 24);

    //calculate minIP
    $minIP = ip2long($sub->getMinHost());
    $maxIP = ip2long($sub->getMaxHost());
    echo "Max-IP: ".$minIP."<br>";
    echo "Max-IP: ".$maxIP."<br>";
    for ($ip = $minIP; $ip <= $maxIP; $ip++) {
        unset($state);
        try{
            $result = $nmap->scan([ long2ip($ip)]);
        } catch (Exception $exception) {
            $state = "error";
        }
        if(empty($state)) {
            if(empty($result[0])) {
                $state = "down";
            }
            else {
                $state = $result[0]->getState();
            }
        }


        $server = new \App\ServerStatus();
        $server->ip = long2ip($ip);
        $server->status = $state;
        $server->save();
    }

});

Route::get("reset", function () {

    $subnet = new \App\Subnet();
    $subnet->name = "Testnetz";
    $subnet->subnet = "10.0.0.0/16";
    $subnet->creator = 31;
    $subnet->save();

    $job_id = dispatch((new \App\Jobs\SubnetCreationJob($subnet))->onQueue("2"));

});

