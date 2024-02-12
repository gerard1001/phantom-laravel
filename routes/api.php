<?php

use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BusController;
use App\Http\Controllers\Api\RouteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user/login', [UserController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', [UserController::class, 'all']);
});

Route::post('/user', [UserController::class, 'store']);
Route::get('/user/{id}', [UserController::class, 'show']);

Route::get('/role', [RoleController::class, 'all']);
Route::get('/role/{id}', [RoleController::class, 'show']);
Route::get('/role/name/{name}', [RoleController::class, 'byName']);

Route::post('/bus', [BusController::class, 'store']);
Route::get('/bus', [BusController::class, 'all']);
Route::get('/bus/{id}', [BusController::class, 'show']);
Route::patch('/bus/route/{id}', [BusController::class, 'assignRoute']);
Route::patch('/bus/user/{id}', [BusController::class, 'assignDriver']);

Route::post('/route', [RouteController::class, 'store']);
Route::get('/route', [RouteController::class, 'all']);
Route::get('/route/{id}', [RouteController::class, 'show']);
Route::put('/route/{id}', [RouteController::class, 'update']);
Route::patch('/route/buses/{id}', [RouteController::class, 'addBuses']);
Route::get('/route/buses/{id}', [RouteController::class, 'getBusesInRoute']);
