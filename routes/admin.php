<?php

Route::middleware('guest:admin')->namespace('Auth')->group(function() {
    Route::get('login', 'LoginController@showLoginForm')->name('login.show-form');
    Route::post('login', 'LoginController@login')->name('login');
});

Route::middleware('auth:admin')->group(function() {
    Route::get('/', 'HomeController@index')->name('index');
    Route::get('dashboard', 'HomeController@dashboard')->name('dashboard');

    Route::prefix('management')->namespace('Management')->as('management.')->group(function() {
        Route::prefix('user')->as('user.')->group(function() {
            Route::post('data', 'UserController@getData')->name('data');
            Route::get('change-password', 'UserController@changePassword')->name('change-password');
            Route::put('update-password', 'UserController@updatePassword')->name('update-password');
            Route::get('{id}/change-password', 'UserController@changeOtherPassword')->name('other.change-password');
            Route::put('{id}/update-password', 'UserController@updateOtherPassword')->name('other.update-password');
            Route::get('login-history', 'UserController@showLoginHistory')->name('login-history');
            Route::post('login-history/data', 'UserController@getLoginHistoryData')->name('login-history.data');
        });
        Route::resource('user', 'UserController', ['except' => ['create', 'show']]);

        Route::resource('role', 'RoleController', ['except' => ['show']]);
        Route::post('role/data', 'RoleController@getData')->name('role.data');
    });

    Route::prefix('preference')->as('preference.')->namespace('Preference')->group(function() {
        Route::get('/', 'PreferenceController@showForm')->name('edit');
        Route::put('/', 'PreferenceController@update')->name('update');
    });

    Route::get('logout', 'Auth\LoginController@logout')->name('logout');
});