<?php

use Illuminate\Support\Facades\Route;

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

/**
 * Corona Form
 */
Route::get('/c/{coronaHash}', 'General@corona');
Route::get('/c/{coronaHash}/data-protection', 'CoronaForm@getDataProtection');

/**
 * Auth
 */
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

/**
 * Export Documents & Images
 */
Route::prefix('/facility/{id}/qr')->group(function() {  // middleware('auth:sanctum')->
    Route::get('/png-300', 'QrCode@png300');
    Route::get('/png-150', 'QrCode@png150');
    Route::get('/svg', 'QrCode@svg');
});

Route::get('/facility/{id}/guest/export', 'ExportDocument@exportGuests');

/**
 * SPA
 */
// override Login routes for SPA
Route::get('login', 'General@spa')->name('login');

Route::get('/{any}', 'General@spa')->where('any', '.*');
