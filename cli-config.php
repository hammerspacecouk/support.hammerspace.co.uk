<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;
// obtaining the entity manager
$entityManager = require __DIR__ . '/tests/SupportService/doctrine-bootstrap.php';
return ConsoleRunner::createHelperSet($entityManager);