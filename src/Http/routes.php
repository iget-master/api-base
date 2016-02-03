<?php

Route::group(['namespace' => 'Iget\ApiBase\Http\Controllers'], function() {
    Route::post('/auth', [
        'as' => 'auth.login',
        'uses' => 'AuthController@getLogin'
    ]);

    Route::resource('user', 'UserController', [
        'except' => [
            'index',
            'create',
            'edit'
        ]
    ]);
});
