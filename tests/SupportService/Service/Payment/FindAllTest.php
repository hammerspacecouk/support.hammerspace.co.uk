<?php

declare(strict_types = 1);
namespace Tests\SupportService\Service\Payment;

use SupportService\Service\PaymentsService;
use Tests\SupportService\AbstractDatabaseTestCase;

class FindAllTest extends AbstractDatabaseTestCase
{
    public function testNoResults()
    {
        $this->assertSame([], $this->getService(PaymentsService::class)->findAll(10, 1));
    }

    public function testCountValue()
    {
        // create db fixtures
        $this->loadFixtures(['SimpleList']);
        $service = $this->getService(PaymentsService::class);

        $results = $service->findAll(10, 1);
        $this->assertCount(2, $results);

        // make sure they are in the right order
        $this->assertEquals(new \DateTimeImmutable('2017-01-02'), $results[0]->getDate());
    }

    public function testPaginated()
    {

    }
}
