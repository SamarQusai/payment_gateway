<?php

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseRequest
{
    public const BaseRequestWebSource = "WEB_SOURCE";
    public const BaseRequestCommandSource = "COMMAND_SOURCE";

    public function __construct(protected ValidatorInterface $validator, string $source = self::BaseRequestWebSource)
    {
        if ($source === self::BaseRequestWebSource) {
            $this->populate();
        }
    }

    public function validate(): ConstraintViolationListInterface
    {
        return $this->validator->validate($this);
    }

    protected function populate(): void
    {
        foreach ($this->getRequest()->toArray() as $property => $value) {
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }

        if ($this instanceof PaymentChargerRequest) {
            $this->cardExpiryDate = $this->cardExpiryMonth ."/".$this->cardExpiryYear;
        }
    }

    public function getRequest(): Request
    {
        return Request::createFromGlobals();
    }
}

