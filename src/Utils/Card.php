<?php

namespace App\Utils;

use Symfony\Component\Validator\Constraints\CardScheme;
class Card
{

    public static function getCardBrand(string $cardNumber):string {
        $brands = [
            CardScheme::VISA => '/^4/',
            CardScheme::MAESTRO => '/^5[1-5]/',
            CardScheme::DISCOVER => '/^6/',
        ];
        foreach ($brands as $brand => $pattern) {
            if (preg_match($pattern, $cardNumber)) {
                return $brand;
            }
        }

        throw new \InvalidArgumentException("Unsupported card brand");
    }
}