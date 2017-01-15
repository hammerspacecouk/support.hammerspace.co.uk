<?php

declare(strict_types = 1);
namespace SupportService\Data\Database\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="payments", indexes={@ORM\Index(name="date_indx", columns={"date"})})
 */
class Payment extends Entity
{
    /** @ORM\Column(type="string", nullable=false) */
    public $name;
    /** @ORM\Column(type="datetime", nullable=false) */
    public $date;
    /** @ORM\Column(type="float", nullable=false) */
    public $amount;
    /** @ORM\Column(type="text", nullable=true) */
    public $message;
    /** @ORM\Column(type="string", nullable=false) */
    public $chargeId;
}
