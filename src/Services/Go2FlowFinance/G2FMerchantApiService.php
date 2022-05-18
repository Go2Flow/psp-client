<?php

namespace Go2Flow\PSPClient\Services\Go2FlowFinance;

use Go2Flow\PSPClient\Services\Go2FlowFinance\Models\Merchant;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Payrexx\Models\Request\Gateway;
use Payrexx\Payrexx;

class G2FMerchantApiService extends Constants
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
     * @param string $instanceName
     * @param string $secret
     * @return array
     * @throws \Payrexx\PayrexxException
     */
    public function getAvailablePaymentMethods(string $instanceName, string $secret): array
    {

        $payrexx = new \Payrexx\Payrexx($instanceName, $secret);

        $provider = new \Payrexx\Models\Request\PaymentProvider();

        $availableMethods = [];

        try {

            $response = $payrexx->getAll($provider);

            foreach ($response as $provider) {
                /**
                 * @var \Payrexx\Models\Response\PaymentProvider $provider
                 */
                $availableMethods = array_merge($availableMethods, $provider->getActivePaymentMethods());
            }


        } catch (\Payrexx\PayrexxException $e) {
            print $e->getMessage();
        }

       return $availableMethods;
    }

    public function getTransactions()
    {
        /**
        $payrexx = new \Payrexx\Payrexx($instanceName, $secret);

        $transaction = new \Payrexx\Models\Request\Transaction();
        //$transaction->setId('d4a81c48');

        try {
        $response = $payrexx->getAll($transaction);
        var_dump($response);
        } catch (\Payrexx\PayrexxException $e) {
        print $e->getMessage();
        }

        dd($response);**/
    }

    public function createGateway(string $instanceName, string $secret, Gateway $gateway): bool|\Payrexx\Models\Response\Gateway
    {

        $payrexx = new Payrexx($instanceName, $secret);

        try {
            $response = $payrexx->create($gateway);
            return $response;
        } catch (\Payrexx\PayrexxException $e) {
           Log::error('Payrexx Error: '.$e->getMessage(), $e->getTrace());
        }
        return false;
    }
}
