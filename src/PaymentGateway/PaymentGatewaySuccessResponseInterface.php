<?php

namespace App\PaymentGateway;

use App\Response\PaymentChargerResponse;

interface PaymentGatewaySuccessResponseInterface
{
    public static function build(array $gatewayResponse): PaymentChargerResponse;
}