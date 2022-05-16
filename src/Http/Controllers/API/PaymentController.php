<?php

namespace Go2Flow\PSPClient\Http\Controllers\API;

use Go2Flow\PSPClient\Services\Go2FlowFinance\G2FApiService;
use Go2Flow\PSPClient\Services\Go2FlowFinance\G2FMerchantApiService;
use Go2Flow\SaasRegisterLogin\Http\Controllers\Controller;
use Go2Flow\SaasRegisterLogin\Http\Requests\Api\TeamCreateRequest;
use Go2Flow\SaasRegisterLogin\Http\Requests\Api\TeamUpdateBankRequest;
use Go2Flow\SaasRegisterLogin\Http\Requests\Api\TeamUpdateGeneralRequest;
use Go2Flow\SaasRegisterLogin\Http\Requests\Api\UserInviteAcceptRequest;
use Go2Flow\SaasRegisterLogin\Http\Resources\Authenticated\UserResource;
use Go2Flow\SaasRegisterLogin\Models\Team;
use Go2Flow\SaasRegisterLogin\Models\Team\Invitation;
use Go2Flow\SaasRegisterLogin\Models\User;
use Go2Flow\SaasRegisterLogin\Repositories\PermissionRepositoryInterface;
use Go2Flow\SaasRegisterLogin\Repositories\TeamRepositoryInterface;
use Go2Flow\SaasRegisterLogin\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class PaymentController extends Controller
{


    public function __construct() {

    }

    public function getAvailablePaymentMethods(): \Illuminate\Http\JsonResponse
    {
        $team = auth()->user();

        $go2finance = new G2FApiService();
        $apiSecret = $go2finance->getMerchantApiSecret($team);

        $go2finance = new G2FMerchantApiService();
        $paymentMethods = $go2finance->getAvailablePaymentMethods($team->psp_instance, $apiSecret);
        return response()->json(['paymentMethods' => $paymentMethods]);
    }

    public function updatePSPConfiguration()
    {
        $go2finance = new G2FApiService();
        $configuration = $go2finance->updatePSPConfiguration();
        return $configuration;
    }
}
