<?php

namespace Go2Flow\PSPClient\Services\Go2FlowFinance;

use Go2Flow\PSPClient\Services\Go2FlowFinance\Models\Merchant;
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
            dd(json_decode($response->getBody()));
        } else {

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

    public function createGateway() {
        $instanceName = 'time2smile';

        $secret = 'ZtOzezdECDiH9bzTrSkJzkcbNdCCjQ';

        $payrexx = new Payrexx($instanceName, $secret);
        $gateway = new Gateway();

        // amount multiplied by 100
        $gateway->setAmount(89.25 * 100);

        // VAT rate percentage (nullable)
        $gateway->setVatRate(7.70);

        //Product SKU
        $gateway->setSku('P01122000');

        // currency ISO code
        $gateway->setCurrency('CHF');

        //success and failed url in case that merchant redirects to payment site instead of using the modal view
        $gateway->setSuccessRedirectUrl('https://www.luftkuss.ch/');
        $gateway->setFailedRedirectUrl('https://www.go2flow.com');
        $gateway->setCancelRedirectUrl('https://www.google.de');

        // empty array = all available psps
        $gateway->setPsp([]);
        //$gateway->setPsp(array(4));
        //$gateway->setPm(['mastercard']);

        // optional: whether charge payment manually at a later date (type authorization)
        $gateway->setPreAuthorization(false);
        // optional: if you want to do a pre authorization which should be charged on first time
        //$gateway->setChargeOnAuthorization(true);

        // optional: whether charge payment manually at a later date (type reservation)
        $gateway->setReservation(false);

        // subscription information if you want the customer to authorize a recurring payment.
        // this does not work in combination with pre-authorization payments.
        //$gateway->setSubscriptionState(true);
        //$gateway->setSubscriptionInterval('P1M');
        //$gateway->setSubscriptionPeriod('P1Y');
        //$gateway->setSubscriptionCancellationInterval('P3M');

        // optional: reference id of merchant (e. g. order number)
        $gateway->setReferenceId(975382);
        //$gateway->setValidity(5);
        $gateway->setLookAndFeelProfile('bdc4d1f9');

        // optional: parse multiple products
        $gateway->setBasket([
            [
                'name' => [
                    1 => 'Dies ist der Produktbeispielname 1 (DE)',
                    2 => 'This is product sample name 1 (EN)',
                    3 => 'Ceci est le nom de l\'échantillon de produit 1 (FR)',
                    4 => 'Questo è il nome del campione del prodotto 1 (IT)'
                ],
                'description' => [
                    1 => 'Dies ist die Produktmusterbeschreibung 1 (DE)',
                    2 => 'This is product sample description 1 (EN)',
                    3 => 'Ceci est la description de l\'échantillon de produit 1 (FR)',
                    4 => 'Questa è la descrizione del campione del prodotto 1 (IT)'
                ],
                'quantity' => 1,
                'amount' => 100
            ],
            [
                'name' => [
                    1 => 'Dies ist der Produktbeispielname 2 (DE)',
                    2 => 'This is product sample name 2 (EN)',
                    3 => 'Ceci est le nom de l\'échantillon de produit 2 (FR)',
                    4 => 'Questo è il nome del campione del prodotto 2 (IT)'
                ],
                'description' => [
                    1 => 'Dies ist die Produktmusterbeschreibung 2 (DE)',
                    2 => 'This is product sample description 2 (EN)',
                    3 => 'Ceci est la description de l\'échantillon de produit 2 (FR)',
                    4 => 'Questa è la descrizione del campione del prodotto 2 (IT)'
                ],
                'quantity' => 2,
                'amount' => 200
            ]
        ]);

        // optional: add contact information which should be stored along with payment
        $gateway->addField($type = 'title', $value = 'mister');
        $gateway->addField($type = 'forename', $value = 'Max');
        $gateway->addField($type = 'surname', $value = 'Mustermann');
        $gateway->addField($type = 'company', $value = 'Max Musterfirma');
        $gateway->addField($type = 'street', $value = 'Musterweg 1');
        $gateway->addField($type = 'postcode', $value = '1234');
        $gateway->addField($type = 'place', $value = 'Musterort');
        $gateway->addField($type = 'country', $value = 'AT');
        $gateway->addField($type = 'phone', $value = '+43123456789');
        $gateway->addField($type = 'email', $value = 'max.muster@payrexx.com');
        $gateway->addField($type = 'date_of_birth', $value = '03.06.1985');
        $gateway->addField($type = 'terms', '');
        $gateway->addField($type = 'privacy_policy', '');

        $gateway->addField($type = 'custom_field_1', $value = '7.9', $name = array(
            1 => 'courzly_fee',
            2 => 'courzly_fee',
            3 => 'courzly_fee',
            4 => 'courzly_fee',
        ));
        $gateway->addField($type = 'custom_field_2', $value = '0.49', $name = array(
            1 => 'courzly_fix',
            2 => 'courzly_fix',
            3 => 'courzly_fix',
            4 => 'courzly_fix',
        ));

        try {
            $response = $payrexx->create($gateway);
            return $response;
        } catch (\Payrexx\PayrexxException $e) {
            print $e->getMessage();
        }
        return false;
    }
}
