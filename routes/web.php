<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

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

Route::get('createtoken', function () {
    if (Auth::check()) {
        $user = auth()->user();
        auth()->user()->createToken('auth_token', [$user->role->name])->plainTextToken;
    }
    return redirect()->back();
})->middleware('auth')->name('createtoken');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('townlist', [TestController::class, 'town'])->name('listtown');
Route::post('townlist', [TestController::class, 'gettown'])->name('gettown');

Route::group(['middleware' => 'auth'], function () {

    Route::get('application', [TestController::class, 'applicationList'])->name('listapplications');
    Route::post('application', [TestController::class, 'applicationCreate'])->name('createapplications');
    Route::delete('application/{id}', [TestController::class, 'applicationDelete'])->name('delapplications');
    Route::post('application/filter', [TestController::class, 'applicationFilter'])->name('filterapplications');

});

