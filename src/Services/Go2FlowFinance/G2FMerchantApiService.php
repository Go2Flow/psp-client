<?php

namespace Go2Flow\PSPClient\Services\Go2FlowFinance;

use Go2Flow\PSPClient\Services\Go2FlowFinance\Models\Merchant;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Payrexx\Models\Request\Gateway;
use Payrexx\Models\Request\PaymentMethod;
use Payrexx\Models\Request\PaymentProvider;
use Payrexx\Models\Request\Payout;
use Payrexx\Models\Request\Transaction;
use Payrexx\Payrexx;

class G2FMerchantApiService extends Constants
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
     * @return false|\Psr\Http\Message\ResponseInterface
     */
    private function sendRequest($method, $path, $payload) {
        try {

            $client = $this->getClient();
            return $client->request($method, $path, $payload);

        } catch (GuzzleException $e) {
            echo "-------------------GUZZLE EXCEPTION-------------------\n";
            echo $e->getResponse()->getBody()->getContents()."\n";
            var_dump($payload);
            echo "------------------------------------------------------\n";
        } catch (\Exception $e) {
            echo "---------------------EXCEPTION------------------------\n";
            echo $e->getMessage()."\n";
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
    public function getAvailablePaymentMethods(string $instanceName, string $secret, ?string $type = null): array
    {

        $payrexx = new Payrexx($instanceName, $secret);

        $provider = new PaymentMethod();

        $availableMethods = [];

        try {
            if ($type) {
                $provider->setFilterPaymentType($type);
            }
            $response = $payrexx->getAll($provider);
            foreach ($response as $provider) {
                /**
                 * @var \Payrexx\Models\Response\PaymentMethod $provider
                 */
                $availableMethods = array_merge($availableMethods, [$provider->getId()]);
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

    /**
     * @param string $instanceName
     * @param string $secret
     * @param $uuid
     * @return \Payrexx\Models\Response\Payout|null
     * @throws \Payrexx\PayrexxException
     */
    public function getPayout(string $instanceName, string $secret, $uuid): ?\Payrexx\Models\Response\Payout
    {
        $payrexx = new Payrexx($instanceName, $secret);

        try {
            $payout = new Payout();
            $payout->setUuid($uuid);
            return $payrexx->getOne($payout);

        } catch (\Payrexx\PayrexxException $e) {
            Log::error('Payrexx Error (getPayouts): '.$e->getMessage(),  ['file' => $e->getFile(), 'line' => $e->getLine()]);
        }

        return null;
    }

    /**
     * @param string $instanceName
     * @param string $secret
     * @param $id
     * @return \Payrexx\Models\Response\Gateway|null
     * @throws \Payrexx\PayrexxException
     */
    public function getGateway(string $instanceName, string $secret, $id): ?\Payrexx\Models\Response\Gateway
    {
        $payrexx = new Payrexx($instanceName, $secret);
        $gateway = new \Payrexx\Models\Request\Gateway();
        $gateway->setId($id);

        try {
            return $payrexx->getOne($gateway);
        } catch (\Payrexx\PayrexxException $e) {
            Log::error('Payrexx Error (getPayouts): '.$e->getMessage(),  ['file' => $e->getFile(), 'line' => $e->getLine()]);
        }

        return null;
    }

    /**
     * @param string $instanceName
     * @param string $secret
     * @param Transaction $transaction
     * @return \Payrexx\Models\Response\Transaction|null
     * @throws \Payrexx\PayrexxException
     */
    public function chargePreAuthorized(string $instanceName, string $secret, Transaction $transaction): ?\Payrexx\Models\Response\Transaction
    {
        $payrexx = new Payrexx($instanceName, $secret);

        try {

            return $payrexx->charge($transaction);

        } catch (\Payrexx\PayrexxException $e) {
            Log::error('Payrexx Error (chargePreAuthorized): '.$e->getMessage(),  ['file' => $e->getFile(), 'line' => $e->getLine(), 'payload' => $transaction->toArray('charge')]);
        }

        return null;
    }
}
