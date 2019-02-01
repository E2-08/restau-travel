<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Menu;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Images;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Entity\Language;
use app\Entity\Restaurant;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    //id	name	adress	phone	email	city	country	timesolt	created_at	slug

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR_fr');
        //Gestion des utilisatteurs
        $Bookers = array();
        $languages = array();
        $genres = ['male', 'female'];
        $restaurator = array();


        $role = new Role();
        $role->setTitle('ROLE_USER');
        $manager->persist($role);

        for ($i = 1; $i <= 10; $i++) {
            $user = new User();

            $genre = $faker->randomElement($genres);

            $avatar = 'https://randomuser.me/api/portraits/';
            $avatarId = $faker->numberBetween(1, 99) . '.jpg';

            $avatar .= ($genre == 'male' ? 'men/' : 'women/') . $avatarId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstname)
                ->setLastName($faker->lastname)
                ->setEmail($faker->email)
                ->setHash($hash)
                ->setAvatar($avatar)
                ->adduserRole($role)
                ->setPhone($faker->phoneNumber);

            $manager->persist($user);
            $Bookers[] = $user;

        }
        

// languages=======================================
        $lang = [
            [
                'name' => 'Français',
                'flag' => 'fr'
            ],
            [
                'name' => 'Anglais',
                'flag' => 'uk'
            ],
            [
                'name' => 'Portugais',
                'flag' => 'po'
            ],
            [
                'name' => 'Nerlandais',
                'flag' => 'nl'
            ],
            [
                'name' => 'Italien',
                'flag' => 'it'
            ],
            [
                'name' => 'Allemand',
                'flag' => 'ge'
            ],
            [
                'name' => 'Spagnol',
                'flag' => 'sp'
            ]
        ];

        //Gestion des language
        for ($i = 0; $i <= count($lang) - 1; $i++) {
            $language = new Language();

            $language->setName($lang[$i]['name']);
            $language->setFlag($lang[$i]['flag']);
            $language->setSlug($lang[$i]['flag']);
            $manager->persist($language);
            $languages[] = $language;
        }
        // =======================================================

        // gestion des resaturants ===============================

        $roleAdmin = new Role();
        $roleAdmin->setTitle('ROLE_RESTAURATOR');
        $manager->persist($roleAdmin);

        for ($i = 1; $i <= 10; $i++) {
            $userAdmin = new User();
            $genre = $faker->randomElement($genres);
            $avatar = 'https://randomuser.me/api/portraits/';
            $avatarId = $faker->numberBetween(1, 99) . '.jpg';
            $avatar .= ($genre == 'male' ? 'men/' : 'women/') . $avatarId;
            $hash = $this->encoder->encodePassword($userAdmin, 'password');

            $userAdmin->setFirstName($faker->firstname)
                ->setLastName($faker->lastname)
                ->setEmail($faker->email)
                ->setHash($hash)
                ->setAvatar($avatar)
                ->adduserRole($roleAdmin)
                ->setPhone($faker->phoneNumber);

            $manager->persist($userAdmin);
            $restaurator[] = $userAdmin;

        }



        for ($i = 1; $i <= 17; $i++) {
            $restaurant = new Restaurant();
            $user = $restaurator[mt_rand(1, count($restaurator) - 1)];
            $language = $languages[mt_rand(1, count($languages) - 1)];

            $restaurant->setName($faker->name)
                ->setCountry($faker->country)
                ->setEmail($faker->email)
                ->setAdress($faker->streetAddress)
                ->setPhone($faker->phoneNumber)
                ->setCity($faker->city)
                ->setTimesolt('24/24')
                ->setCoverimages($i . '.jpg')
                ->setCreatedAt(new \DateTime())
                ->setSlug($faker->slug)
                ->setAuthor($user)
                ->addLanguage($language)
                ->setBookinglimit(mt_rand(1, 10));

            for ($j = 1; $j <= mt_rand(2, 5); $j++) {
                $image = new Images();
                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setRestaurant($restaurant);
                $manager->persist($image);
            }

                //menu
            $entree = ['PIATTO DI 6 CICCHETI', 'PAIN DEL ARTE A LA PERSILLADE', 'FARANDOLE D ANTIPASTI'];
            $plat = ['PIZZA MARGARITA', 'ESCALOPE A LA MILANESE', 'TRIO DEL MARE'];
            $dessert = ['TIRAMISU', 'PANACOTTA FRUIT ROUGE', 'PIZZA NUTELLA'];

            for ($k = 1; $k <= 3; $k++) {
                $menu = new Menu();
                $menu->setTitre($entree[mt_rand(0, count($entree) - 1)])
                    ->setPrise(mt_rand(9, 15) . " euros")
                    ->setCategory("Entrée")
                    ->addRestaurant($restaurant);
                $manager->persist($menu);
            }

            for ($k = 1; $k <= 3; $k++) {
                $menu = new Menu();
                $menu->setTitre($dessert[mt_rand(0, count($dessert) - 1)])
                    ->setPrise(mt_rand(5, 12) . " euros")
                    ->setCategory("Dessert")
                    ->addRestaurant($restaurant);
                $manager->persist($menu);
            }
            for ($k = 1; $k <= 3; $k++) {
                $menu = new Menu();
                $menu->setTitre($plat[mt_rand(0, count($plat) - 1)])
                    ->setPrise(mt_rand(5, 12) . " euros")
                    ->setCategory("Plat")
                    ->addRestaurant($restaurant);
                $manager->persist($menu);
            }
            
             // gestion des réseravtions
            for ($j = 1; $j <= mt_rand(0, 10); $j++) {
                $booking = new Booking();
                $createdAt = $faker->dateTimeBetween('-6 months');
                $startDate = $faker->date($format = 'd/m/Y', $min = 'now');
                $startHour = $faker->time($format = 'H:i', $max = 'now');
                $numberOfPeople = mt_rand(1, 10);
                $booker = $Bookers[mt_rand(1, count($Bookers) - 1)];

                $booking->setBooker($booker)
                    ->setRestaurant($restaurant)
                    ->setStartHour($startHour)
                    ->setStartDate($startDate)
                    ->setNumberOfPeople($numberOfPeople)
                    ->setCreatedAt($createdAt);
                $manager->persist($booking);
               
                //Gestion des commentaires
                $comment = new Comment();
                $comment->setContent($faker->paragraph())
                    ->setRating(mt_rand(1, 5))
                    ->setRestaurant($restaurant)
                    ->setAuthor($booker);
                $manager->persist($comment);
            }
            $manager->persist($restaurant);
        }
        $manager->flush();
    }
}
