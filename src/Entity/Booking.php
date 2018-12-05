<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Restaurant", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $restaurant;

    /**
     * @ORM\Column(type="string")
     * @Assert\Date(message="Attention, la date d'arrivée doit être au bon format !")
     */
    private $startDate;

    /**
     * @ORM\Column(type="string")
     */
    private $startHour;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfPeople;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public $tempDate;
    public $tempTime;
    public $tempPeople;

    /**
     * 
     * @ORM\PrePersist
     * @return void
     */
    public function prePersist()
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }
    }

    public function getId() : ? int
    {
        return $this->id;
    }

    public function getBooker() : ? User
    {
        return $this->booker;
    }

    public function setBooker(? User $booker) : self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getRestaurant() : ? Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(? Restaurant $restaurant) : self
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    public function getStartDate() : ? string
    {
        return $this->startDate;
    }

    public function setStartDate(string $startDate) : self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getStartHour() : ? string
    {
        return $this->startHour;
    }

    public function setStartHour(string $startHour) : self
    {
        $this->startHour = $startHour;

        return $this;
    }

    public function getNumberOfPeople() : ? int
    {
        return $this->numberOfPeople;
    }

    public function setNumberOfPeople(int $numberOfPeople) : self
    {
        $this->numberOfPeople = $numberOfPeople;

        return $this;
    }

    public function getCreatedAt() : ? \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt) : self
    {
        $this->createdAt = $createdAt;

        return $this;
    }


}
