<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OrcaWebHookController;
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

Route::post('/orca-webhook-out',  [OrcaWebHookController::class, 'webhook_out']);
Route::get('/trigger-webhook-in',  [OrcaWebHookController::class, 'trigger_webhook_in']);