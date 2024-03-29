<?php

namespace Go2Flow\PSPClient\Services\Go2FlowFinance;

use Go2Flow\PSPClient\Services\Go2FlowFinance\Models\Bank;
use Go2Flow\PSPClient\Services\Go2FlowFinance\Models\Invoice;
use Go2Flow\PSPClient\Services\Go2FlowFinance\Models\Merchant;
use Go2Flow\SaasRegisterLogin\Models\Team;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Payrexx\Models\Request\Gateway;
use Payrexx\Payrexx;
use Illuminate\Support\Facades\Log;

class G2FApiService extends Constants
{
    /**
     * @var bool|Client
     */
    protected $client = false;

    /**
     * @return Client
     */
    private function getClient()
    {
        if($this->client) {
            return $this->client;
        }

        $this->client = new Client([
            'base_uri' => config('psp-client.api_domain'),
            'headers' => ['X-API-KEY' => config('psp-client.api_key')]
        ]);

        return $this->client;
    }

    /**
     * @param $method
     * @param $path
     * @param $payload
     * @return null|GuzzleHttp\Psr7\Response
     */
    private function sendRequest($method, $path, $payload): ?Response
    {
        try {
            $client = $this->getClient();
            return $client->request($method, $path, $payload);

        } catch (\Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
            Log::error('Payload to previous error', [$method, $path, $payload]);
        }
        return null;
    }

    /**
     * @param Merchant $merchant
     * @return string|null
     */
    public function createMerchant(Merchant $merchant): null|string
    {
        $response = $this->sendRequest( 'POST','service/merchant', [
            'body' => $merchant->toJson(),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        if($response) {
            $responseObj = json_decode($response->getBody());
            if ($responseObj->data && $responseObj->data->id) {
                if (is_string($responseObj->data->id)) {
                    return $responseObj->data->id;
                }
                Log::error('WRONG RESULT IN createMerchant', [$responseObj->data->id]);
            }
            Log::error('WRONG RESULT IN createMerchant', [$response->getBody()]);
        }

        return null;
    }

    /**
     * @param string $merchantId
     * @return string|null
     */
    public function getVerivication(string $merchantId): null|string
    {
        $response = $this->sendRequest( 'GET','service/merchant/'.$merchantId.'/verification', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        if($response) {
           $data = json_decode($response->getBody());
           if ($data->data) {
                return $data->data->status;
           } else {
               Log::error('PSP Error while reading kyc status response.', $data);
           }
        }

        return null;
    }

    /**
     * @param string $merchantId
     * @param string $url
     * @return bool
     */
    public function createWebhook(string $merchantId, string $url): bool
    {
        try {
            $response = $this->sendRequest('POST', 'service/merchant/' . $merchantId . '/api/webhook', [
                'body' => '{"type":"x-form","url":"' . $url . '"}',
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);
            if ($response && $response->getStatusCode() === 200) {
                return true;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
        }

        return false;
    }

    /**
     * @param Team $team
     * @return string|null
     */
    public function getMerchantApiSecret(Team $team): null|string
    {
        $response = $this->sendRequest( 'GET','service/merchant/'.$team->psp_id.'/api/secret', [
            'body' => '',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        if($response) {
            $apiSecret = json_decode($response->getBody());
            return $apiSecret->data->api_secret;
        }

        return null;
    }

    /**
     * @param Merchant $merchant
     * @return \Psr\Http\Message\StreamInterface
     */
    public function updateMerchant(Merchant $merchant): \Psr\Http\Message\StreamInterface
    {
        $response = $this->sendRequest( 'PATCH','service/merchant/'.$merchant->getId(), [
            'body' => $merchant->toJson(),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        return $response->getBody();
    }

    /**
     * @param Bank $bank
     * @return \Psr\Http\Message\StreamInterface
     */
    public function updateBank(Bank $bank): \Psr\Http\Message\StreamInterface
    {
        $response = $this->sendRequest( 'PATCH','service/merchant/'.$bank->getMerchantId().'/verification/bank_account/'.$bank->getCurrency(), [
            'body' => $bank->toJson(),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        return $response->getBody();
    }

    /**
     * @param Team $team
     * @return \Psr\Http\Message\StreamInterface
     */
    public function listBank(Team $team): \Psr\Http\Message\StreamInterface
    {
        $response = $this->sendRequest( 'GET','service/merchant/'.$team->psp_id.'/verification/bank_account', [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        return $response->getBody();
    }

    public function updatePSPConfiguration()
    {
        $response = $this->sendRequest('POST', 'service/merchant/23996647/user/login', [
            'body' => '{"currencies":["EUR","AED","USD"]}',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        dd(json_decode($response->getBody()));

        return $response->getBody();
    }

    /**
     * @param Invoice $invoice
     * @return false|mixed
     */
    public function createInvoice(Invoice $invoice)
    {
        $response = $this->sendRequest( 'POST','service/merchant/'.$invoice->getMerchantId().'/invoice', [
            'body' => $invoice->toJson(),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        if($response && $response->getStatusCode() === 200) {
            return json_decode($response->getBody()->getContents(), true);
        }

        return false;
    }

    /**
     * @param string $merchantId
     * @param string $invoiceId
     * @return false|mixed
     */
    public function getInvoice(string $merchantId, string $invoiceId)
    {
        $response = $this->sendRequest( 'GET','service/merchant/'.$merchantId.'/invoice/'.$invoiceId, [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        if($response && $response->getStatusCode() === 200) {
            return json_decode($response->getBody()->getContents(), true);
        }

        return false;
    }
}
