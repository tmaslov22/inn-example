<?php

namespace App\Entity;

use App\Repository\InnRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=InnRepository::class)
 */
class Inn
{

    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     */
    private $inn;

    /**
     * @ORM\Column(type="json")
     */
    private $payload = [];

    public function getInn(): ?int
    {
        return $this->inn;
    }

    public function setInn(int $inn): self
    {
        $this->inn = $inn;

        return $this;
    }

    public function getPayload(): ?array
    {
        return $this->payload;
    }

    public function setPayload(array $payload): self
    {
        $this->payload = $payload;

        return $this;
    }
}
