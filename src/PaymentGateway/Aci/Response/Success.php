<?php

namespace App\PaymentGateway\Aci\Response;


use App\Response\PaymentChargerResponse;
use App\PaymentGateway\PaymentGatewaySuccessResponseInterface;

class Success implements PaymentGatewaySuccessResponseInterface
{
    public static function build(array $gatewayResponse): PaymentChargerResponse
    {
        $paymentChargerResponse =  new PaymentChargerResponse();
        $paymentChargerResponse->transactionId = $gatewayResponse['id'];
        $paymentChargerResponse->issuedAt = $gatewayResponse['timestamp'];
        $paymentChargerResponse->amount = $gatewayResponse['amount'];
        $paymentChargerResponse->currency = $gatewayResponse['currency'];
        $paymentChargerResponse->cardBin = $gatewayResponse['card']['bin'];
        return $paymentChargerResponse;
    }
}