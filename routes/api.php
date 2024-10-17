<?php

use App\Http\Controllers\LoginController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
/*Route::middleware(['web'])->group(function () {
Route::get('login/microsoft', [LoginController::class, 'redirectToProvider']);
Route::get('login/microsoft/callback', [LoginController::class, 'handleProviderCallback']);
});*/
//Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('web')->group(function () {
    Route::get('login/microsoft', [LoginController::class, 'redirectToProvider']);
    Route::get('login/microsoft/callback', [LoginController::class, 'handleProviderCallback']);
});

Route::get('logout', [LoginController::class, 'logout']);

Route::post('storeUser', [LoginController::class, 'storeUsers'])->name('storeUser');


Route::get('/', function (Request $request) {
   // $key = 'Host';
    //$host = $request->header($key);
    $test = $request->header('env', 'Default Header');
    // $headers = $request->header();
    // $key = "user-agent";
    // return $headers[$key];
    return $test;
});