<?php

namespace Go2Flow\PSPClient\Services\Go2FlowFinance;

use Go2Flow\PSPClient\Services\Go2FlowFinance\Models\Merchant;
use Go2Flow\SaasRegisterLogin\Models\Team;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Payrexx\Models\Request\Gateway;
use Payrexx\Payrexx;

class G2FApiService extends Constants
{
    /**
     * @var bool|Client
     */
    protected $client = false;

    protected $apiKey = "%u%B*CEv4*9hZGzfdR!w2Vn!QpZtn!";

    protected $domain = 'https://api.go2flow.finance/v2.0/';

    /**
     * @return Client
     */
    private function getClient()
    {
        if($this->client) {
            return $this->client;
        }

        $this->client = new Client([
            'base_uri' => $this->domain,
            'headers' => ['X-API-KEY' => $this->apiKey]
        ]);

        return $this->client;
    }

    /**
     * @param $method
     * @param $path
     * @param $payload
     * @return false|\Psr\Http\Message\ResponseInterface
     */
    private function sendRequest($method, $path, $payload) {
        try {

            $client = $this->getClient();
            return $client->request($method, $path, $payload);

        } catch (\Exception $e) {
            echo "---------------------EXCEPTION------------------------\n";
            echo $e->getMessage()."\n";
            echo $e->getResponse()->getBody()->getContents()."\n";
            var_dump($payload);
            echo "------------------------------------------------------\n";
        } catch (GuzzleException $e) {
            echo "-------------------GUZZLE EXCEPTION-------------------\n";
            echo $e->getResponse()->getBody()->getContents()."\n";
            var_dump($payload);
            echo "------------------------------------------------------\n";
        }
        return false;
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
           return json_decode($response->getBody());
        }

        return null;
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
}