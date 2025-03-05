<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\TaskController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth'], function () {

    Route::POST('register', [AuthController::class, 'register']);
    Route::POST('login', [AuthController::class, 'login']);

});

Route::middleware('auth:api')->group(function(){
    Route::POST('logout', [AuthController::class, 'logout']);

    // projects
    Route::POST('/project', [ProjectController::class, 'store']);
    Route::GET('/project/{project}', [ProjectController::class, 'show']);
    Route::PATCH('/project/{project}', [ProjectController::class, 'update']);
    Route::DELETE('/project/{project}', [ProjectController::class, 'destroy']);
   
    // tasks
    Route::GET('/task-list/{project}', [TaskController::class, 'index']);
    Route::POST('/task', [TaskController::class, 'store']);
    Route::GET('/task/{task}', [TaskController::class, 'show']);
    Route::PATCH('/task/{task}', [TaskController::class, 'update']);
    Route::DELETE('/task/{task}', [TaskController::class, 'destroy']);

    // task remarks
    Route::POST('/task/{task}/remarks', [TaskController::class, 'addRemarks']);

    // project list - reports
    Route::GET('/project-list', [ProjectController::class, 'index']);
});