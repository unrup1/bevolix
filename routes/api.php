<?php

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

/**
 * Auth
 */

Route::auth([
    'login' => false,
    'verify' => true,
    'reset' => false
]);
Route::post('/login', 'Api\ApiAuth@login');
// Route::post('/password-forgot', 'ApiAuth@forgotPassword');
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

/**
 * User
 */
Route::prefix('user/{id}')->group(function () {
    Route::get('/', 'Api\ApiUser@getOne');
    Route::post('/', 'Api\ApiUser@update');
    Route::post('/password', 'Api\ApiUser@changePassword');
    Route::delete('/', 'Api\ApiUser@delete');
});

/**
 * Facility
 */
Route::prefix('facility')->group(function () {
    Route::get('/', 'Api\ApiFacility@getAll');
    Route::put('/','Api\ApiFacility@create' );
    Route::get('/{id}', 'Api\ApiFacility@getOne');
    Route::post('/{id}', 'Api\ApiFacility@update');
    Route::delete('/{id}', 'Api\ApiFacility@delete');
    Route::get('/{id}/guest', 'Api\ApiFacility@getGuests');
    Route::get('/{facility}/data-protection', 'Api\ApiFacility@getDataProtection');
    Route::post('/{facility}/data-protection', 'Api\ApiFacility@generateDataProtection');
    Route::delete('/{facility}/data-protection', 'Api\ApiFacility@removeDataProtection');
    Route::post('/{facility}/data-protection/file', 'Api\ApiFacility@uploadDataProtection');
});

/**
 * API for Form
 */
Route::put('/form/{hash}/guest', 'CoronaForm@createGuest');
