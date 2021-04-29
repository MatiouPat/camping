<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServiceRepository")
 */
class Service
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $gived_at;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\PositiveOrZero()
     */
    private $washing_token;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\PositiveOrZero()
     */
    private $drying_token;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\PositiveOrZero()
     */
    private $external_shower;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bill", inversedBy="services")
     */
    private $bill;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entry", inversedBy="services")
     */
    private $entry;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGivedAt(): ?\DateTimeInterface
    {
        return $this->gived_at;
    }

    public function setGivedAt(\DateTimeInterface $gived_at): self
    {
        $this->gived_at = $gived_at;

        return $this;
    }

    public function getWashingToken(): ?int
    {
        return $this->washing_token;
    }

    public function setWashingToken(?int $washing_token): self
    {
        $this->washing_token = $washing_token;

        return $this;
    }

    public function getDryingToken(): ?int
    {
        return $this->drying_token;
    }

    public function setDryingToken(?int $drying_token): self
    {
        $this->drying_token = $drying_token;

        return $this;
    }

    public function getExternalShower(): ?int
    {
        return $this->external_shower;
    }

    public function setExternalShower(?int $external_shower): self
    {
        $this->external_shower = $external_shower;

        return $this;
    }

    public function getBill(): ?Bill
    {
        return $this->bill;
    }

    public function setBill(?Bill $bill): self
    {
        $this->bill = $bill;

        return $this;
    }

    public function getEntry(): ?Entry
    {
        return $this->entry;
    }

    public function setEntry(?Entry $entry): self
    {
        $this->entry = $entry;

        return $this;
    }
}
