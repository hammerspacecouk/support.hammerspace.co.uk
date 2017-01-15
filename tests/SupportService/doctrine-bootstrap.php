<?php

declare(strict_types = 1);

$cachedAnnotationReader = new \Doctrine\Common\Annotations\CachedReader(
    new \Doctrine\Common\Annotations\AnnotationReader(),
    new \Doctrine\Common\Cache\ArrayCache()
);

$evm = new \Doctrine\Common\EventManager();

$conn = [
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/db.sqlite',
];
$config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
    [__DIR__ . "/../../src/SupportService/Data/Database/Entity"],
    true,
    null,
    null,
    false
);
$config->setNamingStrategy(new \Doctrine\ORM\Mapping\UnderscoreNamingStrategy());
$config->addEntityNamespace('SupportService', 'SupportService\Data\Database\Entity');
$config->setSQLLogger(new Doctrine\DBAL\Logging\DebugStack());

// obtaining the entity manager
return \Doctrine\ORM\EntityManager::create($conn, $config, $evm);
