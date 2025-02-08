<?php

namespace App\CustomValidation;

use DateTime;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ExpiryDateValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ExpiryDate) {
            throw new UnexpectedTypeException($constraint, ExpiryDate::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $dateParts = explode("/", $value, );
        if (count($dateParts) < 2) {
            $this->context->buildViolation($constraint->message)
                ->atPath('expiryDate')
                ->addViolation();
            return;
        }

        $expiryMonth = (int)$dateParts[0];
        $expiryYear = (int)$dateParts[1];

        $currentDate = new DateTime();
        $currentYear = (int)$currentDate->format('Y');
        $currentMonth = (int)$currentDate->format('m');

        if ($expiryYear < $currentYear) {
            $this->context->buildViolation($constraint->message)
                ->atPath('expiryYear')
                ->addViolation();
            return;
        }

        if ($expiryYear === $currentYear && $expiryMonth < $currentMonth) {
            $this->context->buildViolation($constraint->message)
                ->atPath('expiryMonth')
                ->addViolation();
            return;
        }
    }
}