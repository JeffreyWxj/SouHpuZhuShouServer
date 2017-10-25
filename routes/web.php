<?php

Route::get('/', 'Admin\IndexController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
