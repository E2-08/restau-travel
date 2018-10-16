<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
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
     * @ORM\Column(type="datetime")
     */
    private $bookingAt;

    /**
     * @ORM\Column(type="time")
     */
    private $bookingtime;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\Column(type="time")
     */
    private $bookingstart;

    /**
     * @ORM\Column(type="time")
     */
    private $bookingend;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="bookings")
     */
    private $client;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Restaurant", inversedBy="bookings")
     */
    private $restaurant;

    public function __construct()
    {
        $this->client = new ArrayCollection();
        $this->restaurant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookingAt(): ?\DateTimeInterface
    {
        return $this->bookingAt;
    }

    public function setBookingAt(\DateTimeInterface $bookingAt): self
    {
        $this->bookingAt = $bookingAt;

        return $this;
    }

    public function getBookingtime(): ?\DateTimeInterface
    {
        return $this->bookingtime;
    }

    public function setBookingtime(\DateTimeInterface $bookingtime): self
    {
        $this->bookingtime = $bookingtime;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getBookingstart(): ?\DateTimeInterface
    {
        return $this->bookingstart;
    }

    public function setBookingstart(\DateTimeInterface $bookingstart): self
    {
        $this->bookingstart = $bookingstart;

        return $this;
    }

    public function getBookingend(): ?\DateTimeInterface
    {
        return $this->bookingend;
    }

    public function setBookingend(\DateTimeInterface $bookingend): self
    {
        $this->bookingend = $bookingend;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getClient(): Collection
    {
        return $this->client;
    }

    public function addClient(User $client): self
    {
        if (!$this->client->contains($client)) {
            $this->client[] = $client;
        }

        return $this;
    }

    public function removeClient(User $client): self
    {
        if ($this->client->contains($client)) {
            $this->client->removeElement($client);
        }

        return $this;
    }

    /**
     * @return Collection|Restaurant[]
     */
    public function getRestaurant(): Collection
    {
        return $this->restaurant;
    }

    public function addRestaurant(Restaurant $restaurant): self
    {
        if (!$this->restaurant->contains($restaurant)) {
            $this->restaurant[] = $restaurant;
        }

        return $this;
    }

    public function removeRestaurant(Restaurant $restaurant): self
    {
        if ($this->restaurant->contains($restaurant)) {
            $this->restaurant->removeElement($restaurant);
        }

        return $this;
    }
}
