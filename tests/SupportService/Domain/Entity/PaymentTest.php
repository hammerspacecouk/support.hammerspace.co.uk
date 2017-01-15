<?php

declare(strict_types = 1);
namespace Tests\SupportService\Domain\Entity;

use SupportService\Domain\Entity\Payment;

class PaymentTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $payment = new Payment(
            $id = 456,
            $name = 'John',
            $message = 'I Love you',
            $amount = 9.87,
            $date = new \DateTimeImmutable()
        );

        $this->assertSame($id, $payment->getId());
        $this->assertSame($name, $payment->getName());
        $this->assertSame($message, $payment->getMessage());
        $this->assertSame($amount, $payment->getAmount());
        $this->assertSame($date, $payment->getDate());
    }

    public function testNullMessage()
    {
        $payment = new Payment(
            987,
            'Robin',
            null,
            1,
            new \DateTimeImmutable()
        );

        $this->assertNull($payment->getMessage());
    }
}
