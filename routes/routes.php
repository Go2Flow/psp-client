<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Go2Flow\PSPClient\Http\Controllers',
    'prefix' => 'psp-client'
], function () {

    Route::group(['middleware' => config('psp-client.middleware', ['web', 'auth'])], function () {

    });
});
