<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Go2Flow\PSPClient\Http\Controllers',
    'prefix' => 'api'
], function () {

    Route::group(['middleware' => ['api'], 'prefix' => 'psp-client'], function () {

        //Route::get('/client/payment/methods', GetClientPaymentMethodsController::class);

    });
});
