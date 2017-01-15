<?php

declare(strict_types = 1);
namespace Tests\SupportService\Mapper;

use SupportService\Data\Database\Entity\Payment as DbPayment;
use SupportService\Data\Database\Mapper\MapperFactory;
use SupportService\Data\Database\Mapper\PaymentMapper;
use SupportService\Domain\Entity\Payment;

class PaymentMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testFromArray()
    {
        $dateIso = '2017-01-01T12:00:00+00:00';
        $mapper = $this->getMapper();
        $domain = $mapper->getDomainModel([
            'id' => $id = 987,
            'name' => $name = 'Mickey',
            'message' => $message = 'Haw haw',
            'amount' => $amount = 4.56,
            'date' => new \DateTime($dateIso),
        ]);
        $this->assertInstanceOf(Payment::class, $domain);
        $this->assertSame($id, $domain->getId());
        $this->assertSame($name, $domain->getName());
        $this->assertSame($message, $domain->getMessage());
        $this->assertSame($amount, $domain->getAmount());
        $this->assertInstanceOf(\DateTimeImmutable::class, $domain->getDate());
        $this->assertSame($dateIso, $domain->getDate()->format('c'));
    }

    public function testFromEntity()
    {
        $mapper = $this->getMapper();
        $entity = $this->createMock(DbPayment::class);
        $dateIso = '2017-01-01T12:00:00+00:00';
        $entity->id = $id = 123;
        $entity->name = $name = 'Minnie';
        $entity->date = new \DateTime($dateIso);
        $entity->message = null;
        $entity->amount = $amount = 4.56;

        $domain = $mapper->getDomainModel($entity);

        $this->assertInstanceOf(Payment::class, $domain);
        $this->assertSame($id, $domain->getId());
        $this->assertSame($name, $domain->getName());
        $this->assertNull($domain->getMessage());
        $this->assertSame($amount, $domain->getAmount());
        $this->assertInstanceOf(\DateTimeImmutable::class, $domain->getDate());
        $this->assertSame($dateIso, $domain->getDate()->format('c'));
    }

    private function getMapper()
    {
        return new PaymentMapper(
            $this->createMock(MapperFactory::class)
        );
    }
}
