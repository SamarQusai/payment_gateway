<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PaymentGatewayApiControllerTest extends WebTestCase
{
    public function testInvalidRequest()
    {
        $client = static::createClient();

        $client->request('POST', '/payment/gateway/api/aci', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'amount' => -100,
            'currency' => 'EUR',
            'cardNumber' => '',
            'cardExpiryYear' => '2025',
            'cardExpiryMonth' => '12',
            'cardCvv' => '123',
            "cardHolder" => "John Doe",
        ]));

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

}