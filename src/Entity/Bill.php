<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BillRepository")
 */
class Bill
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $number;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="integer")
     */
    private $way;

    /**
     * @ORM\Column(type="date")
     */
    private $paided_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Stay", mappedBy="bill")
     */
    private $stays;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Service", mappedBy="bill")
     */
    private $services;

    public function __construct()
    {
        $this->stays = new ArrayCollection();
        $this->services = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getWay(): ?int
    {
        return $this->way;
    }

    public function setWay(int $way): self
    {
        $this->way = $way;

        return $this;
    }

    public function getPaidedAt(): ?\DateTimeInterface
    {
        return $this->paided_at;
    }

    public function setPaidedAt(\DateTimeInterface $paided_at): self
    {
        $this->paided_at = $paided_at;

        return $this;
    }

    /**
     * @return Collection|Stay[]
     */
    public function getStays(): Collection
    {
        return $this->stays;
    }

    public function addStay(Stay $stay): self
    {
        if (!$this->stays->contains($stay)) {
            $this->stays[] = $stay;
            $stay->setBill($this);
        }

        return $this;
    }

    public function removeStay(Stay $stay): self
    {
        if ($this->stays->contains($stay)) {
            $this->stays->removeElement($stay);
            // set the owning side to null (unless already changed)
            if ($stay->getBill() === $this) {
                $stay->setBill(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Service[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->setBill($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->contains($service)) {
            $this->services->removeElement($service);
            // set the owning side to null (unless already changed)
            if ($service->getBill() === $this) {
                $service->setBill(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->number;
    }
}
