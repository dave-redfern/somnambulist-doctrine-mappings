<?php

namespace Somnambulist\Tests\Entities;

use Doctrine\ORM\Mapping as ORM;
use Somnambulist\Collection\Collection;
use Somnambulist\Tests\Entities\ValueObjects\Purchaser;
use Somnambulist\ValueObjects\Types\DateTime\DateTime;
use Somnambulist\ValueObjects\Types\Identity\Uuid;
use Somnambulist\ValueObjects\Types\Money\Money;

/**
 * Class MyEntity
 *
 * @ORM\Entity
 */
class Order
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Uuid
     */
    protected $orderRef;

    /**
     * @var Purchaser
     */
    protected $purchaser;

    /**
     * @var Money
     */
    protected $total;

    /**
     * @var DateTime
     */
    protected $createdAt;

    /**
     * @var Collection
     */
    protected $properties;

    /**
     * Constructor.
     *
     * @param Uuid      $orderRef
     * @param Purchaser $purchaser
     * @param Money     $total
     * @param DateTime  $date
     */
    public function __construct(Uuid $orderRef, Purchaser $purchaser, Money $total, DateTime $date)
    {
        $this->orderRef   = $orderRef;
        $this->purchaser  = $purchaser;
        $this->total      = $total;
        $this->createdAt  = $date;
        $this->properties = new Collection();
    }

    /**
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return Uuid
     */
    public function orderRef()
    {
        return $this->orderRef;
    }

    /**
     * @return Purchaser
     */
    public function purchaser()
    {
        return $this->purchaser;
    }

    /**
     * @return Money
     */
    public function total()
    {
        return $this->total;
    }

    /**
     * @return DateTime
     */
    public function createdAt()
    {
        return $this->createdAt;
    }

    /**
     * @return Collection
     */
    public function properties()
    {
        return $this->properties;
    }
}
