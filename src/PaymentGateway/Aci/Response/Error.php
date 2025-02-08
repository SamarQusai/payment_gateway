<?php

namespace App\PaymentGateway\Aci\Response;

use App\PaymentGateway\PaymentGatewayErrorResponseInterface;

class Error  implements PaymentGatewayErrorResponseInterface
{
    public function __construct(private \Throwable $exception){}


    public function parse(): array {
        $response = $this->exception->getResponse()->toArray(false);
        if (isset($response['result']['code'])) {
            $error['code'] = $response['result']['code'];
            $error['message'] = $response['result']['description'];
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