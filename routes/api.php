<?php

use Go2Flow\PSPClient\Http\Controllers\API\PaymentController;
use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Go2Flow\PSPClient\Http\Controllers',
    'prefix' => 'api'
], function () {

    Route::group(['middleware' => ['api', 'auth:sanctum', 'auth-is-team'], 'prefix' => 'psp-client'], function () {

        Route::get('/go2flow/finance/update/psp/configuration', [PaymentController::class, 'updatePSPConfiguration']);
        Route::get('/go2flow/finance/payment/methods', [PaymentController::class, 'getAvailablePaymentMethods']);

    });
});
