<?php

namespace App\Response;

class PaymentChargerResponse
{

    public string $transactionId;

    public int $issuedAt;


    public float $amount;

    public string $currency;

    public string $cardBin;

    function toArray(): array
    {
        return [
            "transactionId" => $this->transactionId,
            "issuedAt" => $this->issuedAt,
            "amount" => $this->amount,
            "currency" => $this->currency,
            "cardBin" => $this->cardBin,
        ];
    }
}