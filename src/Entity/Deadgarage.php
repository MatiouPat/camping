<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeadgarageRepository")
 */
class Deadgarage
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
    private $started_at;

    /**
     * @ORM\Column(type="date")
     */
    private $stopped_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Stay", inversedBy="deadgarages")
     */
    private $stay;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->started_at;
    }

    public function setStartedAt(\DateTimeInterface $started_at): self
    {
        $this->started_at = $started_at;

        return $this;
    }

    public function getStoppedAt(): ?\DateTimeInterface
    {
        return $this->stopped_at;
    }

    public function setStoppedAt(\DateTimeInterface $stopped_at): self
    {
        $this->stopped_at = $stopped_at;

        return $this;
    }

    public function getDuringDeadGarage(): ?int
    {
        return date_diff($this->started_at, $this->stopped_at)->format('%a');
    }

    public function getStay(): ?Stay
    {
        return $this->stay;
    }

    public function setStay(?Stay $stay): self
    {
        $this->stay = $stay;

        return $this;
    }
}
