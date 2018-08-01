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
Route::get('/test', function() {
    return view("test");
});
Route::post("/login", function(\Illuminate\Http\Request $request ){
    if(\Illuminate\Support\Facades\Auth::attempt([
        "username"=> $request->input("username"),
        "password"=> $request->input("password")
    ])) {
        return true;
    }
});
