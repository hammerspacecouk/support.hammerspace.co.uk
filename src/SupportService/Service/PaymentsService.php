<?php

declare(strict_types=1);
namespace SupportService\Service;

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
        $qb = $this->getQueryBuilder(self::ENTITY);
        $qb = $this->paginate($qb, $limit, $page);
        return $this->getDomainFromQuery($qb, self::ENTITY);
    }
}
