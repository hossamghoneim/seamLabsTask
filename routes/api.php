<?php

use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
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

Route::get('firstTaskPartOne', [TaskController::class, 'fivesEnemy']);
Route::get('firstTaskPartTwo', [TaskController::class, 'searchAlphabet']);
Route::get('firstTaskPartThree', [TaskController::class, 'theLeastStepsToZero']);

Route::middleware('json.response')->group( function () {
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);

    Route::get('get-user', [UserController::class, 'getUser'])->middleware('auth:api');
    Route::get('all-users', [UserController::class, 'allUsers'])->middleware('auth:api');

    Route::post('users-update', [UserController::class, 'updateUser'])->middleware('auth:api');

    Route::delete('users/{id}',[UserController::class,'destroy']);
});
