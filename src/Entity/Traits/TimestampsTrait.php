<?php

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait TimestampsTrait handles created and updated timestamps.
 */
trait TimestampsTrait
{
    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTime  $updatedAt;

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
