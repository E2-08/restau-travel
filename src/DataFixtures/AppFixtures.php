<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Images;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Entity\Language;
use app\Entity\Restaurant;
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
        $users = array();
        $languages = array();
        $genres = ['male', 'female'];

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_RESTORATOR');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName($faker->firstname)
            ->setLastName('Eudes')
            ->setEmail('k.eudes@ici08.fr')
            ->setHash($this->encoder->encodePassword($adminUser, 'password'))
            ->setAvatar("https://pbs.twimg.com/profile_images/838320572404756480/gJ5WSN51_400x400.jpg")
            ->adduserRoles($adminRole);
        $manager->persist($adminUser);

        for ($i = 1; $i <= 10; $i++) {
            $user = new User();

            $genre = $faker->randomElement($genres);

            $avatar = 'https://randomuser.me/api/portraits/';
            $avatarId = $faker->numberBetween(1, 99) . '?jpg';

            $avatar .= ($genre == 'male' ? 'men/' : 'women/') . $avatarId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstname)
                ->setLastName($faker->lastname)
                ->setEmail($faker->email)
                ->setHash($hash)
                ->setAvatar($avatar)
                ->setPhone($faker->phoneNumber);
            $manager->persist($user);
            $users[] = $user;
        }
        //Gestion des language
        for ($i = 1; $i <= 10; $i++) {
            $language = new Language();
            $cCode = $faker->countryCode();
            $language->setName($faker->country($cCode));
            $language->setSlug($cCode);
            $manager->persist($language);
            $languages[] = $language;
        }
        
        // gestion des resaturants
        for ($i = 1; $i <= 10; $i++) {
            $restaurant = new Restaurant();
            $user = $users[mt_rand(1, count($users) - 1)];
            $language = $languages[mt_rand(1, count($languages) - 1)];

            $restaurant->setName($faker->name)
                ->setCountry($faker->country)
                ->setEmail($faker->email)
                ->setAdress($faker->streetAddress)
                ->setPhone($faker->phoneNumber)
                ->setCity($faker->city)
                ->setTimesolt('24/24')
                ->setConverimages($faker->imageUrl)
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
             // gestion des réseravtions
            for ($j = 1; $j <= mt_rand(0, 10); $j++) {
                $booking = new Booking();
                $createdAt = $faker->dateTimeBetween('-6 months');
                $startDate = $faker->date($format = 'd/m/Y', $min = 'now');

                $startHour = $faker->time($format = 'H:i', $max = 'now');


                $numberOfPeople = mt_rand(1, 10);
                $booker = $users[mt_rand(1, count($users) - 1)];

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
