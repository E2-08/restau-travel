<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Images;
use DateTimeInterface;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


/*Prenom
Nom
Nom restaurant
Email
phone
Adresse
code postal
ville
pays
nombre total de reservation/j
je souhaire recevoir les offres
description
Horaires d'ouverture
ticket moyen

Moyens de paiement
type de restaurataion
Ambiance
Cuisine
Déco
Service
Qualité/Prix*/


/**
 * @ORM\Entity(repositoryClass="App\Repository\RestaurantRepository")
 */
class Restaurant
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $timesolt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $coverimages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Images", mappedBy="restaurant", orphanRemoval=true)
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="restaurants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="restaurant")
     */
    private $bookings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="restaurant", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Language", mappedBy="restaurant")
     */
    private $languages;

    /**
     * Undocumented variable
     *
     * @ORM\Column(type="integer")
     */
    private $bookinglimit;


    /**
     * slug initialization
     * 
     *@ORM\PrePersist
     *@ORM\PreUpdate
     *
     * @return void
     */
    public function initializeSlug()
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->name);
        }
    }

    public function __construct()
    {
        $this->notices = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->languages = new ArrayCollection();
    }

    public function getId() : ? int
    {
        return $this->id;
    }

    public function getName() : ? string
    {
        return $this->name;
    }

    public function setName(string $name) : self
    {
        $this->name = $name;

        return $this;
    }

    public function getAdress() : ? string
    {
        return $this->adress;
    }

    public function setAdress(string $adress) : self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPhone() : ? string
    {
        return $this->phone;
    }

    public function setPhone(string $phone) : self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail() : ? string
    {
        return $this->email;
    }

    public function setEmail(string $email) : self
    {
        $this->email = $email;

        return $this;
    }

    public function getCity() : ? string
    {
        return $this->city;
    }

    public function setCity(string $city) : self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry() : ? string
    {
        return $this->country;
    }

    public function setCountry(string $country) : self
    {
        $this->country = $country;

        return $this;
    }

    public function getTimesolt() : ? string
    {
        return $this->timesolt;
    }

    public function setTimesolt(string $timesolt) : self
    {
        $this->timesolt = $timesolt;

        return $this;
    }

    public function getCreatedAt() : ? DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt) : self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    public function getSlug() : ? string
    {
        return $this->slug;
    }

    public function setSlug(string $slug) : self
    {
        $this->slug = $slug;

        return $this;
    }

    public function setConverimages(string $converimages) : self
    {
        $this->coverimages = $converimages;
        return $this;
    }

    public function getConverimages() : ? string
    {
        return $this->coverimages;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings() : Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking) : self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setRestaurant($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking) : self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            $booking->removeRestaurant($this);
        }

        return $this;
    }

    /**
     * @return Collection|Images[]
     */
    public function getImages() : Collection
    {
        return $this->images;
    }

    public function addImage(Images $image) : self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setRestaurant($this);
        }

        return $this;
    }

    public function removeImage(Images $image) : self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getRestaurant() === $this) {
                $image->setRestaurant(null);
            }
        }

        return $this;
    }

    public function getAuthor() : ? User
    {
        return $this->author;
    }

    public function setAuthor(? User $author) : self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments() : Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment) : self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setRestaurant($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment) : self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getRestaurant() === $this) {
                $comment->setRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * Rating average
     * @return float
     */
    public function getRatingsAvg()
    {
        $sum = array_reduce($this->comments->toArray(), function ($total, $comment) {
            return $total + $comment->getRating();
        }, 0);

        if (count($this->comments) > 0) return number_format($sum / count($this->comments), 0, '.', ',');
        return 0;
    }

    /**
     * Permet de récupérer le commentaire d'une annonce par rapport à une annonce
     *
     * @param User $author
     * @return Comment|null
     */
    public function getCommentAuthor(User $author)
    {
        foreach ($this->comments as $comment) {

            if ($comment->getAuthor() === $author) {
                dump($comment);
                return $comment;
            }
        }
        return null;
    }

    /**
     * @return Collection|Language[]
     */
    public function getLanguages() : Collection
    {
        return $this->languages;
    }

    public function addLanguage(Language $language) : self
    {
        if (!$this->languages->contains($language)) {
            $this->languages[] = $language;
            $language->addRestaurant($this);
        }

        return $this;
    }

    public function removeLanguage(Language $language) : self
    {
        if ($this->languages->contains($language)) {
            $this->languages->removeElement($language);
            $language->removeRestaurant($this);
        }

        return $this;
    }

    public function getBookinglimit() : ? int
    {
        return $this->bookinglimit;
    }

    public function setBookinglimit(int $bookinglimit) : self
    {
        $this->bookinglimit = $bookinglimit;

        return $this;
    }



}
