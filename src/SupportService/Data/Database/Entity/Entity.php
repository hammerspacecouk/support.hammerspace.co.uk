<?php

declare(strict_types = 1);
namespace SupportService\Data\Database\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

abstract class Entity
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;
    /** @ORM\Column(type="datetime", nullable=false) */
    public $createdAt;
    /** @ORM\Column(type="datetime", nullable=false) */
    public $updatedAt;

    /**
     * Set createdAt
     *
     * @ORM\PrePersist
     */
    public function onCreate()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * Set updatedAt
     *
     * @ORM\PreUpdate
     */
    public function onUpdate()
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}
