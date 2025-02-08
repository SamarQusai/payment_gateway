<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use App\PaymentGateway\PaymentGatewayErrorResponseInterface;

class PaymentGatewayException extends \Exception
{
    protected array $errors;

    public function __construct(PaymentGatewayErrorResponseInterface $parser)
    {
        $this->errors = $parser->parse();
        parent::__construct($this->errors['message'], Response::HTTP_INTERNAL_SERVER_ERROR, $parser->exception());
    }

    public function getError(): array
    {
        return $this->errors;
    }
}