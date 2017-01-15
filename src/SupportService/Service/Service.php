<?php

declare(strict_types = 1);
namespace SupportService\Service;

use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Psr\Log\LoggerInterface;
use SupportService\Data\Database\Mapper\MapperFactory;

abstract class Service
{
    const TBL = 'tbl';

    const DEFAULT_LIMIT = 50;
    const DEFAULT_PAGE = 1;

    protected $entityManager;
    protected $appTimeProvider;
    protected $mapperFactory;
    protected $logger;

    public function __construct(
        EntityManager $entityManager,
        DateTimeImmutable $appTimeProvider,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->mapperFactory = new MapperFactory();
        $this->appTimeProvider = $appTimeProvider;
        $this->logger = $logger;
    }

    protected function getEntity(string $name): EntityRepository
    {
        return $this->entityManager
            ->getRepository('SupportService:' . $name);
    }

    protected function getQueryBuilder(string $name): QueryBuilder
    {
        $entity = $this->getEntity($name);
        return $entity->createQueryBuilder(self::TBL);
    }

    protected function getDomainFromQuery(
        QueryBuilder $qb,
        string $entityType
    ): array {
        return $this->getDomainFromQueryObject($qb->getQuery(), $entityType);
    }

    protected function getDomainFromQueryObject(
        $query,
        string $entityType
    ): array {
        $result = $query->getArrayResult();
        $entities = [];
        $mapper = $this->mapperFactory->createMapper($entityType);
        foreach ($result as $item) {
            $entities[] = $mapper->getDomainModel($item);
        }
        return $entities;
    }

    protected function getOffset(
        int $limit,
        int $page
    ): int {
        return ($limit * ($page - 1));
    }

    protected function paginate(
        QueryBuilder $qb,
        int $limit,
        int $page
    ): QueryBuilder {
        return $qb->setMaxResults($limit)
            ->setFirstResult($this->getOffset($limit, $page));
    }
}
