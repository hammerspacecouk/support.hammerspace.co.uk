<?php

declare(strict_types = 1);
namespace SupportService\Service;

use DateTimeImmutable;
use SupportService\Data\Database\Entity\Payment as DbPayment;
use SupportService\Domain\Entity\Payment;

class PaymentsService extends Service
{
    const ENTITY = 'Payment';

    public function countAll(): int
    {
        $qb = $this->getQueryBuilder(self::ENTITY);
        $qb->select('count(1)');
        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function findAll(
        int $limit,
        int $page
    ): array {
        $qb = $this->getQueryBuilder(self::ENTITY)
            ->orderBy('tbl.date', 'DESC');
        $qb = $this->paginate($qb, $limit, $page);
        return $this->getDomainFromQuery($qb, self::ENTITY);
    }

    public function createPayment(
        string $name,
        ?string $message,
        float $amount,
        DateTimeImmutable $date,
        string $chargeId
    ): Payment {
        // create a user database entity
        $payment = new DbPayment();
        $payment->name = $name;
        $payment->message = $message;
        $payment->date = $date;
        $payment->amount = $amount;
        $payment->chargeId = $chargeId;

        $this->entityManager->persist($payment);
        $this->entityManager->flush();

        return $this->mapperFactory->createPayment()->getDomainModel($payment);
    }
}
