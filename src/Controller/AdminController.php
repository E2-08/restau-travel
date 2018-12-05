<?php

namespace App\Controller;

use App\Entity\Booking;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\BookingRepository;
use App\Services\StatsService;
use App\Entity\Restaurant;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(ObjectManager $manager, BookingRepository $bookingrepo, StatsService $stat)
    {
        $bookings = new Booking();

        $bookings = $bookingrepo->findCurrent(113);

        $rating = $manager->createQuery('SELECT AVG(u.rating) FROM App\Entity\Comment u where u.restaurant =113')->getSingleScalarResult();

        $bookingscount = $stat->getEntityCount(
            'Booking',
            ['numberOfPeople' => 10]
        );

        $restaurantscount = $stat->getEntityCount('Restaurant', array('id' => 113));
        $commentscount = $stat->getEntityCount('Comment', array('restaurant' => 113));

        return $this->render('admin/restaurant/index.html.twig', [
            'statdata' => compact('restaurantscount', 'bookingscount', 'commentscount', 'rating', 'bookings')
        ]);
    }
}
