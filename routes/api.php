<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TestController;
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

Route::post('townlist', [TestController::class, 'gettown'])->name('gettown');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('application', [TestController::class, 'applicationlist']);
    Route::post('application', [TestController::class, 'applicationCreate']);
    Route::delete('application/{id}', [TestController::class, 'applicationDelete']);
    Route::post('application', [TestController::class, 'applicationFilter']);
});