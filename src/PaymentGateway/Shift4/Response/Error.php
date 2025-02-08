<?php

namespace App\PaymentGateway\Shift4\Response;

use App\PaymentGateway\PaymentGatewayErrorResponseInterface;

class Error implements PaymentGatewayErrorResponseInterface
{
    public function __construct(private \Throwable $exception){}

    public function parse(): array {
        $response = $this->exception->getResponse()->toArray(false);
        if (isset($response['error'])) {
            $error['code'] = $response['error']['type'];
            $error['message'] = $response['error']['message'];
        } else {
            $error = [
                'type' => 'server_error',
                'code' => 'server_error',
                'message' => 'Server error occurred',
            ];
        }
        return $error;
    }

    public function exception(): \Throwable
    {
        return $this->exception;
    }
}