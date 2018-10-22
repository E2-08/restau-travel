<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Restaurant;

use App\Form\RestaurantType;
use App\Repository\RestaurantRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RestaurantController extends AbstractController
{
    /**
     * @Route("/restaurant", name="restaurant_index")
     */
    public function index(RestaurantRepository $repo)
    {
        $restaurants = $repo->findAll();
        // récuperer la liste des restaux prémiun
        return $this->render('restaurant/index.html.twig', [
            'restaurants' => $restaurants
        ]);
    }
    
    /**
     * @Route("/restaurant/add", name="restaurant_add_new")
     * 
     * @return response
     */
    public function create(Request $request, ObjectManager $manager){
      $restaurant = new Restaurant();
      $form = $this->createForm(RestaurantType::class, $restaurant);
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid())
      {
        $manager->persist($restaurant);
        $manager->flush();
      }
      return $this->render('restaurant/addnew.html.twig',[
          'form'=> $form->createView()
      ]);
    }

   /**
     * @Route("/restaurant/{slug}", name="restaurant_show")
     * 
     * @return response
     */
    public function show($slug, RestaurantRepository $repo){
      $restaurant = $repo->findOneBySlug($slug);
      // récuperer la liste des restaux prémiun
      return $this->render('restaurant/restaurant_show.html.twig',
        array("restaurant" => $restaurant)
      );
    }
  
}
