<?php

namespace App\Controller;

use App\Entity\Restaurant;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RestaurantController extends AbstractController
{
    /**
     * @Route("/restaurant", name="restaurant")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Restaurant::class);
        $restaurants = $repo->findAll();
        // récuperer la liste des restaux prémiun
        
        return $this->render('home/home.html.twig', [
            'controller_name' => 'RestaurantController',
            'Restaurants' => $restaurants
        ]);
           var_dump($restaurants);
    }
}
