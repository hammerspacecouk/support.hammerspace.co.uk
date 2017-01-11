<?php

declare(strict_types=1);
namespace SupportService\Domain\Entity;

use DateTimeImmutable;

class Payment extends Entity
{
    private $name;
    private $message;
    private $amount;
    private $date;

    public function __construct(
        int $id,
        string $name,
        ?string $message,
        float $amount,
        DateTimeImmutable $date
    ) {
        parent::__construct($id);

        $this->name = $name;
        $this->message = $message;
        $this->amount = $amount;
        $this->date = $date;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }
}
