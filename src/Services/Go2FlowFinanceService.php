<?php

class Go2FlowFinanceService
{
    public function createMerchant()
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', 'https://api.payrexx.com/v2.0/service/merchant', [
            'body' => '{"send_welcome_mail":true,"activate_psp_36":false}',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        echo $response->getBody();
    }
}
