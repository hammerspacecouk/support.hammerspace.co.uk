<?php

declare(strict_types = 1);
namespace Tests\SupportService\Service\Payment;

use SupportService\Service\PaymentsService;
use Tests\SupportService\AbstractDatabaseTestCase;

class CountAllTest extends AbstractDatabaseTestCase
{
    public function testCountZero()
    {
        // setup the service
        /** @var PaymentsService $service */
        $service = $this->getService(PaymentsService::class);

        $this->assertSame(0, $service->countAll());
    }

    public function testCountValue()
    {
        // create db fixtures
        $this->loadFixtures(['SimpleList']);

        // setup the service
        $service = $this->getService(PaymentsService::class);
        $this->assertSame(2, $service->countAll());
    }
}
