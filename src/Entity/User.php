<?php
namespace App\Entity;
use App\Entity\Comment;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as UserInterfaceAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *  fields={"email"},
 *  message="Un autre utilisateur s'est déja inscrit avec cette adress email, merci de la modifier"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     */
    private $avatar;

    /**
     *
     * $fullName
     */
    private $fullName;




    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Veuillez renseignez un email valide!")
     */
    private $email;

    /**
     * Variable de confirmation de mot de passe
     * @Assert\EqualTo(propertyPath="hash",message="Vous n'avez pas correctement confirmer votre mot de passe !")
     */
    public $passwordConfirme;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Restaurant", mappedBy="author")
     */
    private $restaurants;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="booker")
     */
    private $bookings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author", orphanRemoval=true)
     */
    private $comments;

    private $manager;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", inversedBy="users")
     */
    private $userRole;


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
            $this->slug = $slugify->slugify($this->firstName . ' ' . $this->lastName);
        }
    }


    public function __construct()
    {

        $this->restaurants = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->userRole = new ArrayCollection();

    }
    public function serialize()
    {
    }

    public function unserialize($serialized)
    {
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    public function getId() : ? int
    {
        return $this->id;
    }

    public function getFirstName() : ? string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName) : self
    {
        $this->firstName = $firstName;

        return $this;
    }
    //


    public function getFullName() : ? string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getLastName() : ? string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName) : self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getHash() : ? string
    {
        return $this->hash;
    }

    public function setHash(string $hash) : self
    {
        $this->hash = $hash;

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

    public function getEmail() : ? string
    {
        return $this->email;
    }

    public function setEmail(string $email) : self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Restaurant[]
     */
    public function getRestaurants() : Collection
    {
        return $this->restaurants;
    }

    public function addRestaurant(Restaurant $restaurant) : self
    {
        if (!$this->restaurants->contains($restaurant)) {
            $this->restaurants[] = $restaurant;
            $restaurant->setAuthor($this);
        }

        return $this;
    }

    public function removeRestaurant(Restaurant $restaurant) : self
    {
        if ($this->restaurants->contains($restaurant)) {
            $this->restaurants->removeElement($restaurant);
            // set the owning side to null (unless already changed)
            if ($restaurant->getAuthor() === $this) {
                $restaurant->setAuthor(null);
            }
        }

        return $this;
    }

    public function getPassword()
    {
        return $this->hash;
    }



    public function getUsername()
    {
        return $this->email;
    }


    public function getPhone() : ? string
    {
        return $this->phone;
    }

    public function setPhone(? string $phone) : self
    {
        $this->phone = $phone;

        return $this;
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
            $booking->setBooker($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking) : self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
             // set the owning side to null (unless already changed)
            if ($booking->getBooker() === $this) {
                $booking->setBooker(null);
            }
        }

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
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment) : self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
             // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    public function getRoles()
    {
        $roles = $this->userRole->map(function ($roles) {
            return $roles->getTitle();
        })->toArray();

        $roles[] = 'ROLE_USER';

        return $roles;
    }

    /**
     * @return Collection|Role[]
     */
    public function getUserRole() : Collection
    {
        return $this->userRole;
    }

    public function addUserRole(Role $userRole) : self
    {
        if (!$this->userRole->contains($userRole)) {
            $this->userRole[] = $userRole;
        }

        return $this;
    }

    public function removeUserRole(Role $userRole) : self
    {
        if ($this->userRole->contains($userRole)) {
            $this->userRole->removeElement($userRole);
        }

        return $this;
    }

}
