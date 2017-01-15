<?php

declare(strict_types = 1);
namespace Tests\SupportService\Domain\Entity;

use SupportService\Domain\Entity\Entity;

class EntityTest extends \PHPUnit_Framework_TestCase
{
    public function testId()
    {
        $id = 123;
        $entity = $this->getMockForAbstractClass(Entity::class, [$id]);

        $this->assertSame($id, $entity->getId());
    }
}
