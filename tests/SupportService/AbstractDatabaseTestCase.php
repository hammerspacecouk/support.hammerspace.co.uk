<?php

declare(strict_types = 1);
namespace Tests\SupportService;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\Logging\DebugStack;
use Psr\Log\NullLogger;

class AbstractDatabaseTestCase extends \PHPUnit_Framework_TestCase
{
    private const FIXTURES_PATH = 'Tests\SupportService\DataFixtures\\';

    private $ormExecutor;
    static private $entityManager;

    protected function getService($service)
    {
        return new $service(
            $this->getEntityManager(),
            new \DateTimeImmutable(),
            new NullLogger()
        );
    }

    protected function loadFixtures(array $fixtureNames)
    {
        $loader = new Loader();
        foreach ($fixtureNames as $fixtureName) {
            $fixtureClassName = self::FIXTURES_PATH . $fixtureName;
            $loader->addFixture(new $fixtureClassName());
        }
        $this->getOrmExecutor()->execute($loader->getFixtures());
        // Reset the SQL Logger so we only care about Queries made after the
        // fixtures have loaded
        $this->resetDbQueryLogger();
    }

    protected function getRepository($name)
    {
        return $this->getEntityManager()->getRepository($name);
    }

    protected function getDbQueries()
    {
        return $this->getEntityManager()->getConfiguration()->getSQLLogger()->queries;
    }

    protected function resetDbQueryLogger()
    {
        $this->getEntityManager()->getConfiguration()->setSQLLogger(new DebugStack());
    }

    protected function getOrmExecutor()
    {
//        if (is_null($this->ormExecutor)) {
            $this->ormExecutor = new ORMExecutor($this->getEntityManager(), new ORMPurger());
//        }
        return $this->ormExecutor;
    }

    protected function getEntityManager()
    {
        if (is_null(self::$entityManager)) {
            self::$entityManager = require __DIR__ . '/doctrine-bootstrap.php';
        }
        return self::$entityManager;
    }
}
