<?php

namespace App\Controller;

use DateTime;
use App\Entity\Role;
use App\Entity\User;
use GuzzleHttp\Client;
use App\Entity\Booking;
use App\Entity\Language;
use App\Entity\Restaurant;
use Cocur\Slugify\Slugify;
use App\Form\RestaurantType;
use App\Form\RegistrationType;
use App\Services\StatsService;
use PhpParser\Node\Stmt\UseUse;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Form\RestaurantProfileType;
use App\Repository\BookingRepository;
use App\Repository\CommentRepository;
use App\Repository\LanguageRepository;
use App\Repository\RestaurantRepository;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Exception\RequestException;

use GuzzleHttp\Psr7\Request as PsrRequest;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


class AdminController extends AbstractController
{
    private $isAdmin;
    private $roles;
    private $user;

    public function __construct()
    {


    }



    /**
     * @Route("/admin", name="admin")
     */
    public function index(StatsService $stats)
    {
        if ($this->getUser() != null) {
            $role = $this->getUser()->getRoles();
            // dump($role);
            // die;
            if ($role[0] == 'ROLE_RESTAURATOR') {

                $result = $stats->getOneRestaurantStats($this->getUser());
                if ($result != null) {
                    return $this->redirectToRoute('admin_restaurant');
                }
                if ($result === null) {
                    return $this->redirectToRoute('restaurant_add_new');
                }


            }
            if ($role[0] == 'ROLE_ADMIN') {
                return $this->redirectToRoute('admin_index');
            }
        }
        return $this->redirectToRoute('admin_account_login');
    }
    /**
     * @Route("/adminrt/index", name="admin_index")
     */
    public function appAdmin(StatsService $stats)
    {
        $bestRestaurant = $stats->getRestaurantStats('DESC');
        $worstRestaurant = $stats->getRestaurantStats('ASC');

        return $this->render('admin/app_admin/admin_index.html.twig', [
            'statdata' => $stats->getAppStats(),
            'bestRestaurant' => $bestRestaurant,
            'worstRestaurant' => $worstRestaurant,
            'isAdmin' => true
        ]);
    }

    /**
     * Undocumented function
     *
     * @Route("/adminrest/restaurant", name="admin_restaurant")
     * @param StatsService $stats
     * @return void
     */
    public function restaurant(StatsService $stats)
    {
        $role = $this->getUser()->getRoles();

        $restaurant = $stats->getOneRestaurantStats($this->getUser());
        return $this->render('admin/restaurant/admin_restaurant_index.html.twig', [
            'bookings' => $restaurant['bookings'],
            'bookingCount' => $restaurant['bookingCount'],
            'rating' => $restaurant['rating'],
            'comments' => $restaurant['comments'],
            'commentCount' => $restaurant['commentCount'],
            'isAdmin' => false
        ]);

    }


    public function getAdminStatus()
    {
        if ($this->getUser() != null) {

            $this->user = $this->getUser();
            $this->roles = $this->user->getroles();

            foreach ($this->roles as $role) {
                # code...
                if ($role == 'ROLE_ADMIN') {
                    $this->isAdmin = true;
                }
                if ($role == 'ROLE_RESTAURATOR') {
                    $this->isAdmin = false;
                }
            }
        }
        return $this->isAdmin;
    }

    /**
     * Undocumented function
     * 
     * @Route("/adminrt/index/restaurants", name="admin_restaurants")
     *
     * @param RestaurantRepository $repoRestaurant
     * @return void
     */
    public function indexrestaurents(RestaurantRepository $repoRestaurant)
    {
        $restaurants = $repoRestaurant->findAll();
        return $this->render('admin/app_admin/admin_restaurants.html.twig', [
            'restaurants' => $restaurants,
            'isAdmin' => $this->getAdminStatus()

        ]);
    }

    /**
     * Undocumented function
     *
     * @Route("/adminrt/index/users", name="admin_users")
     * 
     * @param UserRepository $userrepo
     * @return void
     */
    public function indexusers(UserRepository $userrepo)
    {
        $users = $userrepo->findAll();
        return $this->render('admin/app_admin/admin_users.html.twig', [
            'users' => $users,
            'isAdmin' => $this->getAdminStatus()
        ]);
    }

    /**
     * Undocumented function
     * 
     * @Route("/adminrt/index/comments", name="admin_comments")
     * 
     * @param CommentRepository $commentRepo
     * @return void
     */
    public function indexcomments(CommentRepository $commentRepo)
    {
        $comments = $commentRepo->findAll();
        return $this->render('admin/app_admin/admin_comments.html.twig', [
            'comments' => $comments,
            'isAdmin' => $this->getAdminStatus()
        ]);
    }


    /**
     * @Route("/admin/login", name="admin_account_login")
     * 
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        dump($error);

        return $this->render('admin/account/login.html.twig', array(
            'hasError' => $error !== null,
            'username' => $username,
            'isAdmin' => ""
        ));

    }

    /**
     * @Route("/admin/logout", name="admin_account_logout")
     * @return void
     */
    public function logout(AuthenticationUtils $utils)
    {
    }

    /**
     * This function show the register form
     *
     * @Route("/admin/register", name="admin_account_register")
     * 
     * @return Response
     */
    public function register(RoleRepository $roleRepo, Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {

        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $adminRole = new Role();
            $adminRole = $roleRepo->findOneBy(['title' => 'ROLE_RESTAURATOR']);

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

            $user->addUserRole($adminRole);
            $user->setHash($hash);
            $user->setAvatar($fileName);
            $manager->persist($user);
            $manager->flush();

            // //  login after registration
            // $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());

            // $this->get('security.token_storage')->setToken($token);
            // $this->get('session')->set('_security_main', serialize($token));

            $this->addFlash(
                'success',
                "votre compte a bien été crée ! Vous pouver maitenant vous connecter"
            );

            return $this->redirectToRoute('admin');

        }

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
        }

        return $this->render('admin/account/registration.html.twig', array(
            'form' => $form->createView(),
            'isAdmin' => false
        ));

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


    // restaurant admin========================================================================

    /**
     * Undocumented function
     *
     * @Route("/admin/restaurant_profil", name="admin_restaurant_account_profil")
     * @param Request $request
     * @param ObjectManager $manager
     * @param RestaurantRepository $repo
     * @return void
     */
    public function profile(Request $request, ObjectManager $manager, RestaurantRepository $repo, LanguageRepository $repolang)
    {
        $user = $this->getUser();
        $restaurantId = $user->getRestaurants()->current()->getId();
        $restaurant = $repo->findOneById($restaurantId);

        $form = $this->createForm(RestaurantProfileType::class, $restaurant);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $reqs = $request->request->all();

            $bufferLanguage = [];
            foreach ($reqs as $key) {
                if (is_array($key)) {
                    continue;
                }
                $bufferLanguage[$key] = $key;
            }
          
            // Check if  current languages are already used.
            foreach ($restaurant->getLanguages() as $usedLanguage) {
                $isSelect = false;
                foreach ($bufferLanguage as $key => $value) {

                    if (!preg_match("/restaurant/", $bufferLanguage[$key])) {
                        $language = new Language();
                        $newLanguage = $repolang->findOneBy(['name' => $value]);
                        if ($usedLanguage === $language) {
                            $isSelect = true;
                        }
                    }
                }
                if ($isSelect == false) {
                    $restaurant->removeLanguage($newLanguage);
                }
            }
            //add new language to restaurant
            foreach ($bufferLanguage as $key => $value) {
                if (!preg_match("/restaurant/", $bufferLanguage[$key])) {
                    $newLanguage = new Language();
                    $newLanguage = $repolang->findOneBy(['name' => $value]);
                    $restaurant->addLanguage($newLanguage);
                }
            }


            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $form['coverimages']->getData();
            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $file->move(
                    $this->getParameter('coverimages_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            // updates the 'brochure' property to store the PDF file name
            // instead of its contents
            $restaurant->setCoverimages($fileName);

            // ... persist the $product variable or any other work

            $restaurant->setCreatedAt(new DateTime('now'));
            $slugify = new Slugify();
            $slug = $slugify->slugify($restaurant->getName());
            $restaurant->setSlug($slug);
            $restaurant->setAuthor($user);

            $manager->persist($restaurant);
            $manager->flush();
        }


        return $this->render(
            'admin/restaurant/admin_restaurant.html.twig',
            array(
                'form' => $form->createView(),
                'isAdmin' => $this->isAdmin,
                'user' => $user,
                'flagview' => 'info_restaurant',
                'restaurant' => $restaurant,
                'languages' => $restaurant->getLanguages(),
                'catalogLangue' => $repolang->findAll()
            )
        );
    }

    //eudes
    /**
     * Undocumented function
     * 
     * @Route("/admin/account/restaurant", name="admin_restaurant_account")  
     * @Route("/admin/account/restaurant/{slug}", name="restaurant_admin") 
     * @param StatsService $stats
     * @return void
     */
    public function restaurantIndex(StatsService $stats, $slug = "bookings")
    {
        $user = $this->getUser();

        $results = $stats->getOneRestaurantStats($user);
        if ($results != null) {
            return $this->render('admin/restaurant/admin_restaurant.html.twig', [
                'bookings' => $results['bookings'],
                'bookingCount' => $results['bookingCount'],
                'rating' => $results['rating'],
                'comments' => $results['comments'],
                'commentCount' => $results['commentCount'],
                'restaurant' => $results['restaurant'],
                'user' => $user,
                'flagview' => $slug,
                'isAdmin' => false
            ]);
        }
        if ($results === null) {
            return $this->redirectToRoute('admin');
        }
        return $this;

    }


    /**
     * @Route("/admin/restaurant/add", name="restaurant_add_new")
     * 
     * @return response
     */
    public function create(Request $request, ObjectManager $manager, LanguageRepository $repolang)
    {

    // Send an asynchronous request.
        $restaurant = new Restaurant();
        $user = $this->getUser();
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($request->request->get('siret') != null) {
            $siret = $request->request->get('siret');
            $resultats = $this->siretChecker($siret);
            $restaurant->setAdress(str_replace("Siège social -", "", $resultats[0]));
            $restaurant->setName(str_replace("Nom commercial", "", $resultats[3]));

        }


        if ($form->isSubmitted() && $form->isValid()) {

            $reqs = $request->request->all();

            $bufferLanguage = [];
            foreach ($reqs as $key) {
                if (is_array($key)) {
                    continue;
                }
                $bufferLanguage[$key] = $key;
            }
            // dump($bufferLanguage);
            // die;
                 // Check if  current languages are already used.
            foreach ($restaurant->getLanguages() as $usedLanguage) {
                $isSelect = false;
                foreach ($bufferLanguage as $key => $value) {
                    $language = new Language();
                    $newLanguage = $repolang->findOneBy(['name' => $value]);
                    if ($usedLanguage === $language) {
                        $isSelect = true;
                    }
                }
                if ($isSelect == false) {
                    $restaurant->removeLanguage($newLanguage);
                }
            }
            //add new language to restaurant
            foreach ($bufferLanguage as $key => $value) {

                $newLanguage = new Language();
                $newLanguage = $repolang->findOneBy(['name' => $value]);
                $restaurant->addLanguage($newLanguage);

            }


            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $form['coverimages']->getData();
            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $file->move(
                    $this->getParameter('coverimages_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            // updates the 'brochure' property to store the PDF file name
            // instead of its contents
            $restaurant->setCoverimages($fileName);

            // ... persist the $product variable or any other work

            $restaurant->setCreatedAt(new DateTime('now'));
            $slugify = new Slugify();
            $slug = $slugify->slugify($restaurant->getName());
            $restaurant->setSlug($slug);
            $restaurant->setAuthor($user);

            $manager->persist($restaurant);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre profile a bien été mise à jours !"
            );
        }
        

            //redirection vert add menu

        $languages = $repolang->findAll();

        return $this->render('restaurant/addnew.html.twig', [
            'form' => $form->createView(),
            'languages' => $languages,
            'user' => $user,
            'restaurant' => $restaurant,
            'isAdmin' => false,

        ]);
    }


    /**
     * This function check if the siret exist
     * return array()
     * string siret
     */
    protected function siretChecker(string $siret) : array
    {

        $client = new Client();

        $request = new PsrRequest('GET', 'https://www.infogreffe.fr/entreprise-societe/' . $siret);
        $data = array();

        $promise = $client->sendAsync($request)->then(function ($response) use (&$data) {
            if (200 !== $response->getStatusCode()) {

            }

            $crawler = new Crawler($response->getBody()->getContents());
            $crawler->filter('.first .identTitreValeur')->each(function (Crawler $a) use (&$data) {
                foreach ($a as $value) {
                    $data[] = str_replace("Voir le plan", "", $value->textContent);
                }
            });

        });
        $promise->wait();

        return $data;
    }





}
