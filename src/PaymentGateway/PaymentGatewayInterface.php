<?php

namespace App\PaymentGateway;

use App\Request\PaymentChargerRequest;
use App\Response\PaymentChargerResponse;

interface PaymentGatewayInterface
{
    function buildPayload(PaymentChargerRequest $request):array;
    public function charge(PaymentChargerRequest $request):PaymentChargerResponse;
}