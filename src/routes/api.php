<?php

use Illuminate\Support\Facades\Route;

/**
 * Route::middleware('auth:api')->get('/user', function (Request $request) {
 * return $request->user();
 * });
 */

Route::group([], function () {
    Route::post('register', 'ApiController@register');
});

Route::group(['middleware'=>'check.clientToken'], function () {
    Route::post('subscribe', 'ApiController@setSubscription');
    Route::get('subscribe', 'ApiController@getSubscription');
});
