<?php

namespace App\Controller;

use DateTime;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use App\Entity\Restaurant;

use App\Repository\RestaurantRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Services\StatsService;

class BookingController extends AbstractController
{
    /**
     * 
     * @Route("/restaurant/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     */
    public function book(Restaurant $restaurant, Request $request, ObjectManager $manager, StatsService $stat)
    {
        $booking = new Booking();

        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (preg_match('/^(0[0-9]|1[0-9]|2[0-3])h[0-5][0-9]$/', $request->request->get('booking')['tempTime'])) {
                $bookingtime = str_replace('h', "-", $request->request->get('booking')['tempTime']);
                $bookingdate = $request->request->get('booking')['tempDate'];
                $bookingpeople = $request->request->get('booking')['tempPeople'];
              

            //   je récupère le nbre de réseration en function de la date et l'heure
            //   si ce nmbr >= resaturant.limit réservation impossible 
                $validedBooking = $stat->getEntityCount(
                    'Booking',
                    array(
                        'restaurant' => $restaurant->getId(),
                        'startDate' => $bookingdate,
                        'startHour' => $bookingtime
                    )
                );

                if ($validedBooking < $restaurant->getBookinglimit()) {
                    $booking->setBooker($this->getUser());
                    $booking->setStartDate($bookingdate);
                    $booking->setStartHour($bookingtime);
                    $booking->setRestaurant($restaurant);
                    $booking->setNumberOfPeople($bookingpeople);

                    $manager->persist($booking);
                    $manager->flush();

                    return $this->redirectToRoute('booking_show', array('id' => $booking->getId(), 'withAlert' => true));
                }

               //retour
            }


        }
       

        //die;
        return $this->render('booking/book.html.twig', [
            'restaurant' => $restaurant,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/booking/{id}", name="booking_show")
     * @IsGranted("ROLE_USER")
     * 
     * @param Booking $booking
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function show(Booking $booking, Request $request, ObjectManager $manager)
    {

        $comment = new Comment();
        //$restaurant = $repo->findOneById($booking->getRestaurant());
        //$booking->setRestaurant($restaurant);

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setRestaurant($booking->getRestaurant())
                ->setAuthor($this->getUser());
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre commentaire a bien été pris en compte !"
            );
        }



        return $this->render('booking/show.html.twig', array(
            'booking' => $booking,
            'form' => $form->createView()
        ));
    }
}
