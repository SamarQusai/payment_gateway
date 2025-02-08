<?php

namespace App\Tests\PaymentGateway\Shift4\Response;

use App\PaymentGateway\Shift4\Response\Success;
use App\Response\PaymentChargerResponse;
use PHPUnit\Framework\TestCase;

class SuccessTest extends TestCase
{

    public function testSuccess() {
        $id = "5cb97003-4ec2-4d8f-8128-eeb09cd28b45";
        $timestamp = 1739038660000;
        $currency = "EUR";
        $cardBin = "222";
        $amount = 200;
        $request = [
            "id" => $id,
            "created" => $timestamp,
            "amount" => $amount,
            "currency" => $currency,
            "card" => ["first6" => $cardBin]
        ];
        $response = Success::build($request);
        $this->assertInstanceOf(PaymentChargerResponse::class, $response);
        $this->assertEquals($id, $response->transactionId);
        $this->assertEquals($amount, $response->amount);
        $this->assertEquals($timestamp, $response->issuedAt);
        $this->assertEquals($currency, $response->currency);
        $this->assertEquals($cardBin, $response->cardBin);
    }
}