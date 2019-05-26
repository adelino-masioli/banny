<?php
header('Access-Control-Allow-Origin', '*');
header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers',' Origin, Content-Type, Accept, Authorization, X-Request-With');
header('Access-Control-Allow-Credentials',' true');

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/


//routes sale
Route::namespace('Api')->group(function () {
    Route::post('/user', 'UserController@store');
    Route::post('/user/login', 'UserController@login');
    Route::middleware('auth:api')->get('/user', 'UserController@show');
    Route::middleware('auth:api')->put('/user', 'UserController@update');
    Route::middleware('auth:api')->post('/user/destroy', 'UserController@destroy');

    //coupon
    Route::middleware('auth:api')->post('/coupon/store', 'CouponController@store');
    Route::middleware('auth:api')->get('/coupon/all', 'CouponController@all');
    Route::middleware('auth:api')->get('/coupon/last', 'CouponController@last');
    Route::middleware('auth:api')->get('/coupon/detail/{id}', 'CouponController@detail');
    Route::get('/coupon/items', 'CouponItemController@detail');

    //routes admin
    Route::post('/user/login-admin', 'UserController@loginAdmin');
    Route::post('/admin/users', 'UserController@getAll');
});
