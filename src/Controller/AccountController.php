<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * This function is about login viewn and form
     * 
     * @Route("/login", name="account_login")
     * 
     * @return  Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        
        return $this->render('account/login.html.twig',array(
            'hasError' => $error !== null,
            'username' => $username
        ));
    }
    /**
     * This function is about logout
     *
     * @Route("/logout", name="account_logout")
     * @return void
     */
    public function logout(){

    }

    /**
     * This function show the register form
     *
     * @Route("/register", name="acount_register")
     * 
     * @return Response
     */
    public function register(){
        $user = new User();
         $form = $this->createForm(RegistrationType::class,$user);

         return $this->render('account/registration.html.twig',array(
            'form' => $form->createView()
        ));

    }
}
