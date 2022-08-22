<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TestApiController;
use App\Http\Controllers\Api\LoginController;
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
Route::post('login', [LoginController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('townlist', [TestApiController::class, 'gettown']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('application', [TestApiController::class, 'applicationlist']);
    Route::post('application', [TestApiController::class, 'applicationCreate']);
    Route::delete('application/{id}', [TestApiController::class, 'applicationDelete']);
    Route::post('application', [TestApiController::class, 'applicationFilter']);
});