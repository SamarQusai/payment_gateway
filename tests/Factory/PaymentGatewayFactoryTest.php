<?php

namespace App\Tests\Factory;

use App\Factory\PaymentGatewayFactory;
use App\PaymentGateway\Aci\Aci;
use App\PaymentGateway\Shift4\Shift4;
use PHPUnit\Framework\TestCase;

class PaymentGatewayFactoryTest extends TestCase
{
    private PaymentGatewayFactory $factory;

    public function setUp(): void {
        $aciGateway = $this->createMock(Aci::class);
        $shift4Gateway = $this->createMock(Shift4::class);
        $this->factory = new PaymentGatewayFactory($aciGateway, $shift4Gateway);
    }


    public function testMakeAciPaymentGateway() {
        $gateway = $this->factory->make(Aci::NAME);
        $this->assertInstanceOf(Aci::class, $gateway);
    }


    public function testMakeShift4PaymentGateway() {
        $gateway = $this->factory->make(Shift4::NAME);
        $this->assertInstanceOf(Shift4::class, $gateway);
    }

}