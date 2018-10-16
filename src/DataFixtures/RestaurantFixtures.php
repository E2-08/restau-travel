<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use app\Entity\Restaurant;

class RestaurantFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i=0; $i<=10; $i++){
            $restaurant = new Restaurant();
            $restaurant->setName($faker->name)
                ->setCountry($faker->country)
                ->setEmail($faker->email)->setPhone($faker->phoneNumber)
                ->setCity($faker->city)
                ->setTimesolt('24/24')
                ->setCreatedAt(new \DateTime());
            $manager->persist($restaurant);
        }

        $manager->flush();
    }
}
