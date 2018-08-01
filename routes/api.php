<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware("api")->group( function() {
    Route::post("/user/login", "API\Usermanagement\PublicUserManagement@postUserLogin");
    Route::get("/user/login", "API\Usermanagement\PublicUserManagement@getUserLogin");
    Route::get("/actions/{uid?}", "API\ActionLog\PrivateActionLog@getActionLog");

    Route::post("/setup", "API\Setup\PublicSetup@postStartSetup");
});

Route::middleware("auth:api")->group(function(){

    Route::post("/user", "API\Usermanagement\PrivateUserManagement@postUserCreate");
    Route::get("/users/{username?}", "API\Usermanagement\PrivateUserManagement@getUsers");



});