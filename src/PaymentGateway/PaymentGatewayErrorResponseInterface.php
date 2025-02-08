<?php

namespace App\PaymentGateway;

interface PaymentGatewayErrorResponseInterface
{
    public function parse(): array;

    public function exception(): \Throwable;

}