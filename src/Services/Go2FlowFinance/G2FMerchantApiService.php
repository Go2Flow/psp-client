<?php

namespace Go2Flow\PSPClient\Services\Go2FlowFinance;

use Go2Flow\PSPClient\Services\Go2FlowFinance\Models\Merchant;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Payrexx\Models\Request\Gateway;
use Payrexx\Models\Request\PaymentProvider;
use Payrexx\Models\Request\Transaction;
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

        $payrexx = new Payrexx($instanceName, $secret);

        $provider = new PaymentProvider();

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
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getFile(),
                'secret' => $secret,
                'instance_name' => $instanceName,
            ]);
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

    /**
     * @param string $instanceName
     * @param string $secret
     * @param Gateway $gateway
     * @return bool|\Payrexx\Models\Response\Gateway
     * @throws \Payrexx\PayrexxException
     */
    public function createGateway(string $instanceName, string $secret, Gateway $gateway): bool|\Payrexx\Models\Response\Gateway
    {

        $payrexx = new Payrexx($instanceName, $secret);

        try {

            return $payrexx->create($gateway);

        } catch (\Payrexx\PayrexxException $e) {
           Log::error('Payrexx Error (createGateway): '.$e->getMessage(),  [
               'file' => $e->getFile(),
               'line' => $e->getLine(),
               'secret' => $secret,
               'instance_name' => $instanceName,
           ]);
        }

        return false;
    }

    /**
     * @param string $instanceName
     * @param string $secret
     * @param Transaction $transaction
     * @return \Payrexx\Models\Response\Transaction|null
     * @throws \Payrexx\PayrexxException
     */
    public function createRefund(string $instanceName, string $secret, Transaction $transaction): ?\Payrexx\Models\Response\Transaction
    {
        $payrexx = new Payrexx($instanceName, $secret);

        try {

            return $payrexx->refund($transaction);

        } catch (\Payrexx\PayrexxException $e) {
            Log::error('Payrexx Error (createRefund): '.$e->getMessage(),  ['file' => $e->getFile(), 'line' => $e->getLine(), 'payload' => $transaction->toArray('refund')]);
        }

        return null;
    }
}
