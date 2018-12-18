<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LanguageRepository")
 */
class Language
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $flag;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Restaurant", inversedBy="languages",))
     */
    private $restaurant;

    public function __construct()
    {
        $this->restaurant = new ArrayCollection();
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

    public function getFlag() : ? string
    {
        return $this->flag;
    }

    public function setFlag(? string $flag) : self
    {
        $this->flag = $flag;

        return $this;
    }

    public function getSlug() : ? string
    {
        return $this->slug;
    }

    public function setSlug(? string $slug) : self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Restaurant[]
     */
    public function getRestaurant() : Collection
    {
        return $this->restaurant;
    }

    public function addRestaurant(Restaurant $restaurant) : self
    {
        if (!$this->restaurant->contains($restaurant)) {
            $this->restaurant[] = $restaurant;
        }

        return $this;
    }

    public function removeRestaurant(Restaurant $restaurant) : self
    {
        if ($this->restaurant->contains($restaurant)) {
            $this->restaurant->removeElement($restaurant);
        }

        return $this;
    }
}
