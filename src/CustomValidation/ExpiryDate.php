<?php

namespace App\CustomValidation;

use Symfony\Component\Validator\Constraint;

#[\Attribute] class ExpiryDate extends Constraint
{
    public string $message = 'Your card expiry date is invalid.';
}