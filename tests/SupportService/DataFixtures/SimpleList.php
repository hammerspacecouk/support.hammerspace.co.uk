<?php

declare(strict_types = 1);
namespace Tests\SupportService\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use SupportService\Data\Database\Entity\Payment;

class SimpleList extends AbstractFixture
{
    private $manager;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $payment1 = new Payment();
        $payment1->name = 'Name1';
        $payment1->amount = 1.11;
        $payment1->chargeId = 'c1';
        $payment1->date = new \DateTimeImmutable('2017-01-01');
        $this->manager->persist($payment1);

        $payment2 = new Payment();
        $payment2->name = 'Name2';
        $payment2->amount = 2.22;
        $payment2->chargeId = 'c2';
        $payment2->date = new \DateTimeImmutable('2017-01-02');
        $this->manager->persist($payment2);

        $this->manager->flush();
    }
}
