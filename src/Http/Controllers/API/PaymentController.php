<?php

namespace Go2Flow\PSPClient\Http\Controllers\API;

use Go2Flow\PSPClient\Services\Go2FlowFinance\G2FApiService;
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

    public function users()
    {
        $team = currentTeam();
        if ($team) {
            return UserResource::collection(currentTeam()->users->load('roles'));
        }
        abort('401', 'Unauthenticated');
    }

    public function createGateway()
    {
        $go2finance = new G2FApiService();
        $gateway = $go2finance->createGateway();
        dd('Gateway created', $gateway);
    }

    public function updatePSPConfiguration()
    {
        $go2finance = new G2FApiService();
         $go2finance->updatePSPConfiguration();
        dd('Currencies added');
    }
}
