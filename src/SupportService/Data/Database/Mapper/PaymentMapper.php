<?php

declare(strict_types = 1);
namespace SupportService\Data\Database\Mapper;

use DateTimeImmutable;
use SupportService\Data\Database\Entity\Payment as DbPayment;
use SupportService\Domain\Entity\Payment;

class PaymentMapper extends Mapper
{
    public function getDomainModel($item): Payment
    {
        if ($item instanceof DbPayment) {
            $item = get_object_vars($item);
        }

        $date = $item['date'];
        if (!($date instanceof DateTimeImmutable)) {
            $date = DateTimeImmutable::createFromMutable($date);
        }

        return new Payment(
            $item['id'],
            $item['name'],
            $item['message'],
            $item['amount'],
            $date
        );
    }
}
