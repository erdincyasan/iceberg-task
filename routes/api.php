<?php

use App\Http\Controllers\API\AppointmentsController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ContactsController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
Route::post("login",[AuthController::class,"login"]);
Route::post("register",[AuthController::class,"register"]);
Route::middleware("auth:sanctum")->group(function () {
    Route::resource('users',UserController::class);
    Route::resource('appointment', AppointmentsController::class);
    Route::resource("contacts",ContactsController::class);
});