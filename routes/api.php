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

Route::group(['prefix' => 'spider', 'as' => 'spider.'], function () {
	Route::get('verify-img', 'Api\SpiderController@verifyImg')->name('verify-img');
});

Route::group(['prefix' => 'wxapi', 'as' => 'wxapi.'], function () {
	Route::get('js-code-to-openid', 'Api\WxApiController@jsCodeToOpenId')->name('js-code-to-openid');
});
