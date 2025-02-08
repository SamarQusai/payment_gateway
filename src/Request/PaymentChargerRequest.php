<?php

namespace App\Request;

use App\CustomValidation\ExpiryDate;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;

class PaymentChargerRequest extends BaseRequest
{
    #[Assert\Positive(message: "Invalid amount. amount should be greater than 0")]
    #[Assert\NotBlank(message: "Amount should not be blank.")]
    #[SerializedName('amount')]
    public float $amount;

    #[Assert\Currency(message: "Invalid currency.")]
    #[Assert\NotBlank(message: "Currency should not be blank.")]
    public string $currency;

    #[Assert\CardScheme(
        schemes: [Assert\CardScheme::VISA, Assert\CardScheme::MASTERCARD],
        message: 'Your credit card number is invalid.',
    )]
    #[Assert\NotBlank(message: "Your credit card number should not be blank.")]
    public string $cardNumber;

    #[Assert\NotBlank(message: "Your card expiry year should not be blank.")]
    #[Assert\GreaterThanOrEqual(value: "2025", message: "Your card expiry year should be greater than or equal to the current year.")]
    public string $cardExpiryYear;

    #[Assert\NotBlank(message: "Your card expiry month should not be blank.")]
    #[Assert\Range(notInRangeMessage: "Your card expiry month should be between 1 and 12.", min: 1, max: 12)]
    public string $cardExpiryMonth;

    #[ExpiryDate]
    protected $cardExpiryDate;

    #[Assert\NotBlank(message: "Your cvv code should not be blank.")]
    #[Assert\Length(exactly: 3, exactMessage: "Your cvv code length is invalid.")]
    public string $cardCvv;

    #[Assert\NotBlank(message: "Your card holder's name should not be blank.")]
    public string $cardHolder;
}