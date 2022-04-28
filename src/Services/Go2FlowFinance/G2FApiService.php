<?php

namespace Go2Flow\PSPClient\Services\Go2FlowFinance;

use Go2Flow\PSPClient\Services\Go2FlowFinance\Models\Merchant;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

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
     * @return \Psr\Http\Message\StreamInterface
     */
    public function createMerchant(Merchant $merchant): \Psr\Http\Message\StreamInterface
    {
        $response = $this->sendRequest( 'POST','service/merchant', [
            'body' => $merchant->toJson(),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        return $response->getBody();
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
}
