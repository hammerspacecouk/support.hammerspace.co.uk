<?php

declare(strict_types = 1);
namespace Tests\SupportService\Data\Database\Entity;

use SupportService\Data\Database\Entity\Entity;

class EntityTest extends \PHPUnit_Framework_TestCase
{
    public function testId()
    {
        $entity = $this->getMockForAbstractClass(Entity::class);

        $this->assertNull($entity->createdAt);
        $this->assertNull($entity->updatedAt);

        $entity->onCreate();
        $created = $entity->createdAt;
        $updated = $entity->updatedAt;

        $this->assertInstanceOf(\DateTimeImmutable::class, $created);
        $this->assertInstanceOf(\DateTimeImmutable::class, $updated);

        $entity->onUpdate();
        $this->assertInstanceOf(\DateTimeImmutable::class, $entity->createdAt);
        $this->assertInstanceOf(\DateTimeImmutable::class, $entity->updatedAt);

        $this->assertSame($entity->createdAt, $created); // must not change
        $this->assertNotSame($entity->updatedAt, $created); // must have changed
    }
}
