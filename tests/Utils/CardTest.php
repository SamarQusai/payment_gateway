<?php

namespace App\Tests\Utils;

use App\Utils\Card;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\CardScheme;

class CardTest extends TestCase
{
    function testGetCardVisaBrand()
    {
        $cardNumber = "4111111145551142";
        $expectedCardBrand = CardScheme::VISA;
        $cardBrand = Card::getCardBrand($cardNumber);
        $this->assertEquals($expectedCardBrand, $cardBrand);
    }

    function testGetCardMasterCardBrand()
    {
        $cardNumber = "5454 5454 5454 5454";
        $expectedCardBrand = CardScheme::MAESTRO;
        $cardBrand = Card::getCardBrand($cardNumber);
        $this->assertEquals($expectedCardBrand, $cardBrand);
    }

    function testGetCardDiscoverBrand()
    {
        $cardNumber = "6011 6011 6011 6611";
        $expectedCardBrand = CardScheme::DISCOVER;
        $cardBrand = Card::getCardBrand($cardNumber);
        $this->assertEquals($expectedCardBrand, $cardBrand);
    }

    function testGetCardUnsupportedBrand()
    {
        $cardNumber = "3700 0000 0000 002";
        $this->expectException(\InvalidArgumentException::class);
        Card::getCardBrand($cardNumber);
    }

}