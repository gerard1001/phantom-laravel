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
    Route::get('/user', [UserController::class, 'all'])->middleware('restrictRole:operator');
    Route::post('/user', [UserController::class, 'store'])->middleware('restrictRole:admin');
    Route::get('/user/{id}', [UserController::class, 'show'])->middleware(['restrictRole:operator', 'restrictRole:admin']);

    Route::get('/role', [RoleController::class, 'all'])->middleware('restrictRole:admin');
    Route::get('/role/{id}', [RoleController::class, 'show'])->middleware('restrictRole:admin');
    Route::get('/role/name/{name}', [RoleController::class, 'byName'])->middleware('restrictRole:admin');
    
    Route::post('/bus', [BusController::class, 'store'])->middleware(['restrictRole:operator', 'restrictRole:admin']);
    Route::patch('/bus/route/{id}', [BusController::class, 'assignRoute'])->middleware('restrictRole:operator');
    Route::patch('/bus/user/{id}', [BusController::class, 'assignDriver'])->middleware('restrictRole:operator');
    
    
    Route::post('/route', [RouteController::class, 'store'])->middleware(['restrictRole:operator', 'restrictRole:admin']);
    Route::put('/route/{id}', [RouteController::class, 'update']);
    Route::patch('/route/buses/{id}', [RouteController::class, 'addBuses'])->middleware('restrictRole:operator');
    Route::get('/route/buses/{id}', [RouteController::class, 'getBusesInRoute'])->middleware('restrictRole:operator');
});

Route::get('/bus', [BusController::class, 'all']);
Route::get('/bus/{id}', [BusController::class, 'show']);

Route::get('/route', [RouteController::class, 'all']);
Route::get('/route/{id}', [RouteController::class, 'show']);
