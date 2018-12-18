<?php
namespace App\Services;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;


class StatsService
{

    private $manager;
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }


    /**
     * With this function we get entity count
     *
     * @param string $entityName
     * @param array $condition
     * @return void
     */
    public function getEntityCount(string $entityName, array $condition = [])
    {
        return $this->manager->createQuery($this->getQuery($entityName, $condition))->getSingleScalarResult();
    }

    /**
     * Factorisation 
     *
     * @param string $entity
     * @param array $cond
     * @return string
     */
    private function getQuery(string $entity, array $cond = []) : string
    {
        $sql = 'SELECT COUNT(b) FROM App\Entity\\' . $entity . ' b';
        $i = 0;
        if (!empty($cond)) {
            $sql = $sql . ' where ';
            foreach ($cond as $key => $value) {
                $sql = $sql . ' b.' . $key . ' = ' . $value;
                $i = $i + 1;
                if ($i < count($cond)) {
                    $sql = $sql . ' and';
                }
            }
        }
        return $sql;
    }
    public function getAppStats()
    {
        $restaurantscount = $this->getEntityCount('Restaurant');
        $commentscount = $this->getEntityCount('Comment');
        $bookingscount = $this->getEntityCount('Booking');
        $userscount = $this->getEntityCount('user');
        $rating = $this->manager->createQuery('SELECT AVG(u.rating) FROM App\Entity\Comment u where u.restaurant =113')->getSingleScalarResult();
        return compact('userscount', 'restaurantscount', 'bookingscount', 'commentscount', 'bookings', 'rating');
    }

    /**
     * With this function We get Best od worst resraurant
     * return array
     */
    public function getRestaurantStats($order)
    {
        return $this->manager->createQuery('SELECT AVG(c.rating) as note, r.name,r.id,r.city, u.firstName,u.lastName 
            FROM App\Entity\Comment c
            JOIN c.restaurant r
            JOIN r.author u
            GROUP BY r
            ORDER BY note ' . $order)
            ->setMaxResults(5)
            ->getResult();
    }

    public function getOneRestaurantStats(User $user)
    {
        $bookings = [];
        $bookingCount;
        $rating;
        $commentCount;
        $comments = [];


        $restaurants = $user->getRestaurants();

        foreach ($restaurants as $restaurant) {
            # code...
            foreach ($restaurant->getBookings() as $booking) {
            # code... réservation du jour 
                $bookings[] = $booking;
            }
            foreach ($restaurant->getComments() as $comment) {
            # code... réservation du jour 
                $comments[] = $comment;
            }

            $rating = $restaurant->getRatingsAvg();
            $commentCount = count($restaurant->getComments());
            $bookingCount = count($bookings);
            return compact('bookings', 'rating', 'commentCount', 'comments', 'bookingCount', 'restaurant');
        }

    }

}