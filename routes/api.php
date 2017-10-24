<?php


Route::group(['prefix' => 'spider', 'as' => 'spider.'], function () {
	Route::get('verify-img', 'Api\SpiderController@verifyImg')->name('verify-img');
});

Route::group(['prefix' => 'wxapi', 'as' => 'wxapi.'], function () {
	Route::get('js-code-to-openid', 'Api\WxApiController@jsCodeToOpenId')->name('js-code-to-openid');
});

Route::group(['prefix' => 'student', 'as' => 'student.'], function () {
	Route::post('login', 'Api\StudentController@login')->name('login');
	Route::get('student-info', 'Api\StudentController@studentInfo')->name('student-info');
});
