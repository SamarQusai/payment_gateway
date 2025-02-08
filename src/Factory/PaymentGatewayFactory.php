<?php

namespace App\Factory;

use App\PaymentGateway\Aci\Aci;
use App\PaymentGateway\Shift4\Shift4;
use App\PaymentGateway\PaymentGatewayInterface;

class PaymentGatewayFactory
{
    private Aci $aci;
    private Shift4 $shift4;

    public function __construct(Aci $aci, Shift4 $shift4)
    {
        $this->aci = $aci;
        $this->shift4 = $shift4;
    }

    public function make(string $gateway): PaymentGatewayInterface {
        return match ($gateway) {
            Aci::NAME => $this->aci,
            Shift4::NAME => $this->shift4,
            default => throw new \InvalidArgumentException('Invalid payment gateway.'),
        };
    }
}