<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Restaurant;
use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use App\Repository\RestaurantRepository;
use App\Repository\RoleRepository;
use App\Repository\BookingRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{


  /**
   *  @var [string]
   *
   */
  private $flagview;


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
    
    return $this->render('account/login.html.twig', array(
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
  public function logout()
  {

  }

  /**
   * This function show the register form
   *
   * @Route("/register", name="account_register")
   * 
   * @return Response
   */
  public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
  {

    $user = new User();

    $form = $this->createForm(RegistrationType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      $hash = $encoder->encodePassword($user, $user->getHash());
      $file = $form['avatar']->getData();
      $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

        // Move the file to the directory where brochures are stored
      try {
        $file->move(
          $this->getParameter('avatars_directory'),
          $fileName
        );
      } catch (FileException $e) {
                // ... handle exception if something happens during file upload
        throw new Exception($e->getMessage());
      }


      $user->setHash($hash);
      $user->setAvatar($fileName);
      $manager->persist($user);
      $manager->flush();


      $this->addFlash(
        'success',
        "votre compte a bien été crée ! Vous pouver maitenant vous connecter"
      );
      return $this->redirectToRoute('account_login');

    }



    if ($form->isSubmitted() && $form->isValid()) {
      $manager->persist($user);
      $manager->flush();
    }

    return $this->render('account/registration.html.twig', array(
      'form' => $form->createView()
    ));

  }

  /**
   * Permer de d'affichier et de modifier le formulaire de modification de profil
   * 
   * @Route("/account/profile", name="account_profile")
   * 
   * @return Response
   */
  public function profile(Request $request, ObjectManager $manager, RoleRepository $repo)
  {

    $user = $this->getUser();

    $form = $this->createForm(AccountType::class, $user);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      $manager->persist($user);
      $manager->flush();
        
        //Message flash
      $this->addFlash(
        'success',
        "Les données du profil ont été enregistrée avec succès"
      );
    }
    return $this->render(
      'account/profile.html.twig',
      array(
        'form' => $form->createView()
      )
    );


  }

  /**
   * Undocumented function
   * @Route("/account/info",name="account_info")
   * @param Request $request
   * @param ObjectManager $manager
   * @return void
   */
  public function aboutMe(Request $request, ObjectManager $manager)
  {
    $user = $this->getUser();
    $updatePassword = new PasswordUpdate();

    $formPassword = $this->createForm(PasswordUpdateType::class, $updatePassword);
    $formPassword->handleRequest($request);

    $formInfo = $this->createForm(AccountType::class, $user);
    $formInfo->handleRequest($request);

    if ($formInfo->isSubmitted() && $formInfo->isValid()) {

      $manager->persist($user);
      $manager->flush();
        
        //Message flash
      $this->addFlash(
        'success',
        "Les données du profil ont été enregistrée avec succès"
      );
    }


    if ($formPassword->isSubmitted() && $formPassword->isValid()) {

      if (!password_verify($updatePassword->getOldPassword(), $user->getHash())) {
        $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez saisi n'est
          pas votre mode de passe actuel "));
      }

      if (password_verify($updatePassword->getOldPassword(), $user->getHash())) {
        try {
          $newPassword = $updatePassword->getNewPassword();
          $hash = $encoder->encodePassword($user, $newPassword);
          $user->setHash($hash);
          $manager->persist($user);
          $manager->flush();
        } catch (\Doctrine\DBAL\Exception $e) {

        }
      }

    }
    // ==========================================================

    $this->flagview = "info";
    $this->userStatus();
    return $this->render("user/index.html.twig", array(
      "user" => $this->getUser(),
      "formInfo" => $formInfo->createView(),
      "formpassword" => $formPassword->createView(),
      "flagview" => $this->flagview
    ));


  }

  public function userBookingList()
  {

  }





  // /**
  //  * Permer de d'affichier et de modifier le formulaire de modification de profil
  //  * 
  //  * @Route("/account/password-update", name="account_updatepaword")
  //  * 
  //  * @return Response
  //  */
  // public function updatePassword(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
  // {
  //   $updatePassword = new PasswordUpdate();
  //   $user = $this->getUser();

  //   $form = $this->createForm(PasswordUpdateType::class, $updatePassword);
  //   $form->handleRequest($request);

  //   if ($form->isSubmitted() && $form->isValid()) {

  //     if (!password_verify($updatePassword->getOldPassword(), $user->getHash())) {
  //       $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez saisi n'est
  //         pas votre mode de passe actuel "));

  //     } else {
  //       try {
  //         $newPassword = $updatePassword->getNewPassword();
  //         $hash = $encoder->encodePassword($user, $newPassword);
  //         $user->setHash($hash);
  //         $manager->persist($user);
  //         $manager->flush();
  //       } catch (\Doctrine\DBAL\Exception $e) {

  //       }
  //     }

  //   }

  //   return $this->render('account/password.html.twig', array(
  //     'form' => $form->createView()
  //   ));
  // }

  /**
   * Undocumented function 
   * @Route("/account",name="account_index")
   * @return Response
   */
  public function myAccount()
  {
    $this->userStatus();
    return $this->render("user/index.html.twig", array(
      "user" => $this->getUser(),
      "flagview" => "booking"
    ));
  }

  public function userStatus()
  {
    if ($this->getUser() == null) {
      return $this->redirectToRoute('account_login');
    }
    return true;
  }



  /**
   * Undocumented function
   * @Route("/account/restaurant",name="account_index_restaurants")
   *
   * @return Response
   */
  public function myRestaurant()
  {
    return $this->render("index/restaurant.html.twig", array(
      "user" => $this->getUser(),
      "bufferType" => 'restaurant'
    ));
  }


  /**
   * Undocumented function
   * @Route("/account/booking",name="account_booking")
   * @IsGranted("ROLE_USER")
   *
   * @return Response
   */
  public function myBooking()
  {

    return $this->myAccount();
  }


  //Gobal function
  /**
   * @return string
   */
  private function generateUniqueFileName()
  {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
    return md5(uniqid());
  }

}
