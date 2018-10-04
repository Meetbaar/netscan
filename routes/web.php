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

Route::get("reset", function () {

    $subnet = new \App\Subnet();
    $subnet->name = "Testnetz";
    $subnet->subnet = "10.96.0.0/16";
    $subnet->creator = 31;
    $subnet->save();

    $job_id = dispatch((new \App\Jobs\SubnetCreationJob($subnet))->onQueue("2"));

});
