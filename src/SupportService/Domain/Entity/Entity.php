<?php

declare(strict_types=1);
namespace SupportService\Domain\Entity;

abstract class Entity
{
    protected $id;

    public function __construct(
        int $id
    ) {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
