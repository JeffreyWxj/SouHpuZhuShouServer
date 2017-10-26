<?php

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
  // Auth::routes(); Auth路由
  Route::group([], function () {
    // Authentication Routes...
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    // Registration Routes...
    // Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    // Route::post('register', 'Auth\RegisterController@register');
    // Password Reset Routes...
    // Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    // Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    // Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    // Route::post('password/reset', 'Auth\ResetPasswordController@reset');
  });
  // 需要登录的
  Route::group(['middleware' => ['auth']], function () {
    //Index控制器
    Route::group(['prefix' => 'index', 'as' => 'index.'], function () {
      Route::get('index', 'Admin\IndexController@index')->name('index');
    });
    //System控制器
    Route::group(['prefix' => 'system', 'as' => 'system.'], function () {
      Route::get('env-setting', 'Admin\SystemController@envSetting')->name('env-setting');
      Route::post('term-start', 'Admin\SystemController@termStart')->name('term-start');
    });
  });
});
