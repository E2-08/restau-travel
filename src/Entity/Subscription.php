<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubscriptionRepository")
 */
class Subscription
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Restaurant", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $subscriber;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Offer", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $offer;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime")
     */
    private $subscriptionStart;

    /**
     * @ORM\Column(type="datetime")
     */
    private $subscriptionEnd;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubscriber(): ?Restaurant
    {
        return $this->subscriber;
    }

    public function setSubscriber(Restaurant $subscriber): self
    {
        $this->subscriber = $subscriber;

        return $this;
    }

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(Offer $offer): self
    {
        $this->offer = $offer;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getSubscriptionStart(): ?\DateTimeInterface
    {
        return $this->subscriptionStart;
    }

    public function setSubscriptionStart(\DateTimeInterface $subscriptionStart): self
    {
        $this->subscriptionStart = $subscriptionStart;

        return $this;
    }

    public function getSubscriptionEnd(): ?\DateTimeInterface
    {
        return $this->subscriptionEnd;
    }

    public function setSubscriptionEnd(\DateTimeInterface $subscriptionEnd): self
    {
        $this->subscriptionEnd = $subscriptionEnd;

        return $this;
    }


}
