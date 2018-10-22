<?php

namespace App\DataFixtures;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Images;
use app\Entity\Restaurant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RestaurantFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }
    //id	name	adress	phone	email	city	country	timesolt	created_at	slug

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR_fr');
        //Gestion des utilisatteurs
        $users = array();
        $genres = ['male', 'female'];

        for($i =1 ; $i <= 10; $i++){
            $user = new User();
            
            $genre = $faker->randomElement($genres);

            $avatar = 'https://randomuser.me/api/portraits/';
            $avatarId =  $faker->numberBetween(1,99) . '?jpg';

            $avatar .= ($genre == 'male' ? 'men/' : 'women/'). $avatarId;

            $hash = $this->encoder->encodePassword($user,'password');

            $user->setFirstName($faker->firstname)
                 ->setLastName($faker->lastname)
                 ->setEmail($faker->email)
                 ->setHash($hash)
                 ->setAvatar($avatar);

                 $manager->persist($user);
                 $users[] = $user;
        }

        // gerstion des resaturants
        for ($i=1; $i<=10; $i++){
            $restaurant = new Restaurant();
            $user = $users[mt_rand(1, count($users)-1)];
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
                ->setAuthor($user);

             for ($j=1; $j<= mt_rand(2,5); $j++){
                $image = new Images();
                $image->setUrl($faker->imageUrl())
                      ->setCaption($faker->sentence())
                      ->setRestaurant($restaurant);
                 $manager->persist($image);
             }
             $manager->persist($image);
        }
        $manager->flush();
    }
}
