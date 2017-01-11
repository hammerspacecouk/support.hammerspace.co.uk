<?php

declare(strict_types=1);
namespace SupportService\Data\Database\Mapper;

use SupportService\Domain\Entity\Payment;

class PaymentMapper extends Mapper
{
    public function getDomainModel(array $item): Payment
    {
        $id = $item['id'];

        $currency = new Payment(
            $id,
            $item['name']
        );
        return $currency;
    }
}
