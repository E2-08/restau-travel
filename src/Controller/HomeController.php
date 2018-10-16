<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        // repo resateu
        
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/home", name="homepage")
     */
    public function home()
    {
     return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
