<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {

    Route::post('login', 'Api\AuthController@login');
    Route::post('signup', 'Api\AuthController@signup');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'Api\AuthController@logout');
        Route::get('user', 'Api\AuthController@user');
    });
    
});

Route::group(['prefix' => 'wallets'], function () {
    
    Route::group(['middleware' => 'auth:api'], function() {
        Route::post('find', 'Api\WalletController@findWallet');
        Route::put('update', 'Api\WalletController@updateWallet');
        Route::post('purchase', 'Api\WalletController@purchase');
        Route::post('purchase/verified', 'Api\WalletController@purchaseVerified');
    });

});
