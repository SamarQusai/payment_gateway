<?php

namespace App\Service;

use App\Factory\PaymentGatewayFactory;
use App\Request\PaymentChargerRequest;
use App\Response\PaymentChargerResponse;

class Payment
{
    private PaymentGatewayFactory $factory;

    public function __construct(PaymentGatewayFactory $paymentGatewayFactory)
    {
        $this->factory = $paymentGatewayFactory;
    }

    function processPayment(PaymentChargerRequest $request, string $gateway) : PaymentChargerResponse
    {
        $paymentGateway = $this->factory->make($gateway);
        return $paymentGateway->charge($request);
    }
}