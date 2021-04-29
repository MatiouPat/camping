<?php

namespace App\Entity;

use App\Calendar;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StayRepository")
 */
class Stay
{

    const SPOTS = ["A1","A2","A3","A4","B1","B2","B3","B4","C1","C2","C3","C4","C5","D1","D2","D3","D4","E1","E2","E3","E4","E5","F1","F2","F3","G1","G2","G3","G4","G5","H1","H2","H3","H4","H5","H6","I1","I2","I3","I4","I5","I6","J1","J2","J3","J4","J5","J6","K1","K2","L1","L2","L3","M1","M2","M3","M4","M5","N1","N2","N3","N4","N5","N6","N7","O1","O2","O3","O4"];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $arrived_at;

    /**
     * @ORM\Column(type="date")
     */
    private $leaved_at;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $spot;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero()
     */
    private $adult = 1;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero()
     */
    private $teenager = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero()
     */
    private $child = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero()
     */
    private $car = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero()
     */
    private $motor_bike = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero()
     */
    private $bike = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero()
     */
    private $camping_car = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero()
     */
    private $caravan = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero()
     */
    private $tent = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero()
     */
    private $wooden_caravan = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $registration;

    /**
     * @ORM\Column(type="boolean")
     */
    private $electricity = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero()
     */
    private $pet = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $booking = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entry", inversedBy="stays")
     * @Assert\NotNull(message="Un client doit être sélectionné dans la liste ci-dessous")
     */
    private $entry;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bill", inversedBy="stays")
     */
    private $bill;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Deadgarage", mappedBy="stay")
     */
    private $deadgarages;

    public function __construct()
    {
        $this->deadgarages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormattedId(): ?string
    {
        $r = "S";
        for ($i = 0; $i < (10 - strlen(strval($this->id))); $i++){
            $r .= "0";
        }
        $r .= strval($this->id);
        return $r;
    }

    public function getInformation(\DateTime $date) : array
    {
        $payment = false;
        $today_leaved = false;
        if ($this->bill){
            $payment = true;
        }
        if ($this->leaved_at == $date){
            $today_leaved = true;
        }
        return [
            "id" => $this->getEntry()->getId(),
            "spot" => $this->spot,
            "entry" => $this->getEntry()->getGenderName() . " " . $this->getEntry()->getSurname() . " " . $this->getEntry()->getName(),
            "paid" => $payment,
            "today_leaved" => $today_leaved,
            "adult" => $this->adult,
            "teenager" => $this->teenager,
            "child" => $this->child,
            "tent" => $this->tent,
            "car" => $this->car,
            "caravan" => $this->caravan,
            "camping_car" => $this->camping_car,
            "registration" =>$this->registration,
            "electricity" => $this->electricity,
            "booking" => $this->booking,
            "leaved_at" => $this->leaved_at->format('Y-m-d')

        ];
    }

    public function getArrivedAt(): ?\DateTimeInterface
    {
        return $this->arrived_at;
    }

    public function setArrivedAt(?\DateTimeInterface $arrived_at): self
    {
        $this->arrived_at = $arrived_at;

        return $this;
    }

    public function getLeavedAt(): ?\DateTimeInterface
    {
        return $this->leaved_at;
    }

    public function setLeavedAt(?\DateTimeInterface $leaved_at): self
    {
        $this->leaved_at = $leaved_at;

        return $this;
    }

    public function getDuringStay(): ?int
    {
        return date_diff($this->arrived_at, $this->leaved_at)->format('%a');
    }

    public function getSpot(): ?string
    {
        return $this->spot;
    }

    public function setSpot(?string $spot): self
    {
        $this->spot = $spot;

        return $this;
    }

    public function getAdult(): ?int
    {
        return $this->adult;
    }

    public function setAdult(?int $adult): self
    {
        $this->adult = $adult;

        return $this;
    }

    public function getTeenager(): ?int
    {
        return $this->teenager;
    }

    public function setTeenager(?int $teenager): self
    {
        $this->teenager = $teenager;

        return $this;
    }

    public function getChild(): ?int
    {
        return $this->child;
    }

    public function setChild(?int $child): self
    {
        $this->child = $child;

        return $this;
    }

    public function getCar(): ?int
    {
        return $this->car;
    }

    public function setCar(?int $car): self
    {
        $this->car = $car;

        return $this;
    }

    public function getMotorBike(): ?int
    {
        return $this->motor_bike;
    }

    public function setMotorBike(?int $motor_bike): self
    {
        $this->motor_bike = $motor_bike;

        return $this;
    }

    public function getBike(): ?int
    {
        return $this->bike;
    }

    public function setBike(?int $bike): self
    {
        $this->bike = $bike;

        return $this;
    }

    public function getCampingCar(): ?int
    {
        return $this->camping_car;
    }

    public function setCampingCar(?int $camping_car): self
    {
        $this->camping_car = $camping_car;

        return $this;
    }

    public function getCaravan(): ?int
    {
        return $this->caravan;
    }

    public function setCaravan(?int $caravan): self
    {
        $this->caravan = $caravan;

        return $this;
    }

    public function getTent(): ?int
    {
        return $this->tent;
    }

    public function setTent(?int $tent): self
    {
        $this->tent = $tent;

        return $this;
    }

    public function getWoodenCaravan(): ?int
    {
        return $this->wooden_caravan;
    }

    public function setWoodenCaravan(?int $wooden_caravan): self
    {
        $this->wooden_caravan = $wooden_caravan;

        return $this;
    }

    public function getRegistration(): ?string
    {
        return $this->registration;
    }

    public function setRegistration(?string $registration): self
    {
        $this->registration = $registration;

        return $this;
    }

    public function getElectricity(): ?bool
    {
        return $this->electricity;
    }

    public function setElectricity(?bool $electricity): self
    {
        $this->electricity = $electricity;

        return $this;
    }

    public function getPet(): ?int
    {
        return $this->pet;
    }

    public function setPet(?int $pet): self
    {
        $this->pet = $pet;

        return $this;
    }

    public function getBooking(): ?bool
    {
        return $this->booking;
    }

    public function setBooking(?bool $booking): self
    {
        $this->booking = $booking;

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

    public function getBill(): ?Bill
    {
        return $this->bill;
    }

    public function setBill(?Bill $bill): self
    {
        $this->bill = $bill;

        return $this;
    }

    /**
     * @return Collection|Deadgarage[]
     */
    public function getDeadgarages(): Collection
    {
        return $this->deadgarages;
    }

    public function addDeadgarage(Deadgarage $deadgarage): self
    {
        if (!$this->deadgarages->contains($deadgarage)) {
            $this->deadgarages[] = $deadgarage;
            $deadgarage->setStay($this);
        }

        return $this;
    }

    public function removeDeadgarage(Deadgarage $deadgarage): self
    {
        if ($this->deadgarages->contains($deadgarage)) {
            $this->deadgarages->removeElement($deadgarage);
            // set the owning side to null (unless already changed)
            if ($deadgarage->getStay() === $this) {
                $deadgarage->setStay(null);
            }
        }

        return $this;
    }

    /*Validation forms*/

    /**
     * @Assert\Callback()
     * @param ExecutionContextInterface $context
     * @param $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        /*if (($this->car > 0 || $this->camping_car > 0) && $this->registration === null){
            $context->buildViolation('Une plaque d\'immatriculation doit être saisie')
                ->atPath('registration')
                ->addViolation();
        }*/

        if (!($this->arrived_at < $this->leaved_at)){
            $context->buildViolation('La date de départ doit être supérieur à la date d\'arrivée')
                ->atPath('arrived_at')
                ->addViolation();
        }

        if ($this->arrived_at->format('Y-m-d') === Calendar::getTodayDate()->format('Y-m-d') && $this->spot === null){
            $context->buildViolation('Un emplacement doit être saisie')
                ->atPath('spot')
                ->addViolation();
        }
    }
}
