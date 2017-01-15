<?php

declare(strict_types = 1);
namespace Tests\SupportService\Service\Payment;

use SupportService\Data\Database\Entity\Payment as DbPayment;
use SupportService\Domain\Entity\Payment;
use SupportService\Service\PaymentsService;
use Tests\SupportService\AbstractDatabaseTestCase;

class CreatePaymentTest extends AbstractDatabaseTestCase
{
    public function testCreation()
    {
        $this->loadFixtures([]);
        /** @var PaymentsService $service */
        $service = $this->getService(PaymentsService::class);
        $payment = $service->createPayment(
            $name = 'Timmy',
            $message = 'Yah',
            $amount = 1.23,
            $date = new \DateTimeImmutable('2017-01-10T12:00:00+00:00'),
            $chargeId = 'crg_123'
        );

        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertSame($name, $payment->getName());
        $this->assertSame($message, $payment->getMessage());
        $this->assertSame($amount, $payment->getAmount());
        $this->assertSame($date, $payment->getDate());

        // should have been put in the database under ID 1. Go fetch it back
        $result = $this->getEntityManager()->find('SupportService:Payment', 1);
        $this->assertInstanceOf(DbPayment::class, $result);
        $this->assertSame($name, $result->name);
        $this->assertSame($message, $result->message);
        $this->assertSame($amount, $result->amount);
        $this->assertSame($date, $result->date);
        $this->assertSame($chargeId, $result->chargeId);
    }

    public function testNullMessage()
    {

    }
}
