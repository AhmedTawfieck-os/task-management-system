<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);

Route::group(["middleware" => "api"], function(){

    Route::group(["middleware" => "role:manager"], function(){
    Route::apiResource('tasks', TaskController::class);
    Route::post('assign-user-to-task/{task}', [TaskController::class, 'assignUserToTask']);
    Route::apiResource('users', UserController::class);
    });

    Route::group(["middleware" => "role:user"], function(){
        Route::get('retrieve-tasks-of-user', [UserController::class, 'getTasks']);
        Route::get('retrieve-single-task-of-user/{task}', [UserController::class, 'getSingleTask']); 
        Route::put('update-task-status/{task}', [UserController::class, 'updateStatus']);  
    });
    
    Route::post('logout', [AuthController::class, 'logout']);
});