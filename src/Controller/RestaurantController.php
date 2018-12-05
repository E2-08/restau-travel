<?php

namespace App\Controller;

use App\Entity\Images;
use GuzzleHttp\Client;
use App\Entity\Language;
use App\Entity\Restaurant;
use App\Form\RestaurantType;
use App\Form\PropertySearchType;
use App\Entity\PropertySearch;
use App\Repository\LanguageRepository;
use Psr\Http\Message\ResponseInterface;
use App\Repository\RestaurantRepository;
use Symfony\Component\DomCrawler\Crawler;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request as PsrRequest;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class RestaurantController extends AbstractController
{


  /**
   * This method displays the home page and the list of restaurants
   * It also loads the search engine.
   *  
   * @Route("/restaurant", name="restaurant_index")
   * @Route("/restaurant/search", name="restaurant_search")
   * @Route("/restaurant/{page<\d+>?1}", name="restaurant_pagination")
   *
   * @param integer $page
   * @param Request $request
   * @param ObjectManager $manager
   * @param RestaurantRepository $repoRestaurant
   * @param LanguageRepository $repoLanguage
   * @return response
   */
  public function index($page = 1, Request $request, ObjectManager $manager, RestaurantRepository $repoRestaurant, LanguageRepository $repoLanguage)
  {
    $restaurants = new Restaurant();
    $search = new PropertySearch();

    $limit = 5;
    $offset = $page * $limit - $limit;

    $restaurants = $repoRestaurant->findBy([], [], $limit, $offset);

    $form = $this->createForm(PropertySearchType::class, $search);
    $form->handleRequest($request);
    // moteur de recherche
    if ($form->isSubmitted() && $form->isValid()) {
      try {

        $restaurants = $repoRestaurant->search($search, $limit, $offset);

      } catch (Exception $e) {
        echo 'Exception reçue : ', $e->getMessage(), "\n";
      }
    }

    $totalpages = count($restaurants);
    $pageacount = ceil($totalpages / $limit);


    return $this->render('restaurant/index.html.twig', [
      'restaurants' => $restaurants,
      'form' => $form->createView(),
      'pageacount' => $pageacount,
      'currentpage' => $page
    ]);
  }

  /**
   * @Route("/restaurant/add", name="restaurant_add_new")
   * 
   * @return response
   */
  public function create(Request $request, ObjectManager $manager)
  {

    // Send an asynchronous request.
    $restaurant = new Restaurant();
    $form = $this->createForm(RestaurantType::class, $restaurant);
    $form->handleRequest($request);


    $resultats = $this->siretChecker('79211384700018');

    if ($form->isSubmitted() && $form->isValid()) {

      $restaurant->setAuthor($this->getUser());
      $manager->persist($restaurant);
      $manager->flush();
    }

    return $this->render('restaurant/addnew.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route("/restaurant/{slug}", name="restaurant_show")
   * 
   * @return response
   */
  public function show($slug, RestaurantRepository $repo)
  {
    $restaurant = new Restaurant();
    $restaurant = $repo->findOneBySlug($slug);
      // récuperer la liste des restaux prémiun
    return $this->render(
      'restaurant/restaurant_show.html.twig',
      array("restaurant" => $restaurant)
    );
  }
    //Edit @security("is_granted('ROLE_') and user === restaurant.getUser()", message)


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

    return array(
      '0' => 'Siège social - TOLAZO ROUTE DE CAZAUX DOMAINE LA PINEDA 33260 LA TESTE-DE-BUCH',
      '1' => 'Siret - TOLAZO 792 113 847 00018',
      '2' => 'Sigle null',
      '3' => 'Nom commercial LE RESTAURANT LE CAMPING',
      '4' => 'Enseigne LE RESTAURANT LE CAMPING',
      '5' => 'null',
      '6' => 'Forme juridique Société par actions simplifiée'
    );
  }

  /**
   * @Route("/restaurant/{slug}/edit", name="restaurant_edit")
   * 
   * @return response
   */
  public function edit(Request $request, Restaurant $restaurant, ObjectManager $manager)
  {

    $form = $this->createForm(RestaurantType::class, $restaurant);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      $restaurant->setAuthor($this->getUser());
      $manager->persist($restaurant);
      $manager->flush();

        // add flash
      $this->addFlash(
        'success',
        'Les modification du restaurant <strong>{$restaurant->getName()}</strong> ont bien été enregistrées !'
      );

        // return redirectToroute
      return $this->redirectToroute('account_index_restaurants', [
        'slug' => 'restau'
      ]);
    }

    return $this->render('restaurant/edit.html.twig', [
      'form' => $form->createView(),
      'restaurant' => $restaurant

    ]);

  }

  /**
   * Permet de supprimer un restaurant
   * 
   *@Route("/restaurant/{slug}/delete", name="restaurant_delete")
   *@Security("is_granted('ROLE_USER') and user == restaurant.getAuthor()")
   *@param Request $request
   *@param Restaurant $restaurant
   *@param ObjectManager $manager
   *@return response
   */
  public function delete(Request $request, Restaurant $restaurant, ObjectManager $manager)
  {

    $manager->remove($restaurant);
    $manager->flush();

    $this->addFlash(
      'success',
      'Le restaurant' . $restaurant->getName() . ' ont bien été supprimer de la liste de vos restaurants'
    );

        // return redirectToroute
    return $this->redirectToroute('account_index_restaurants', [
      'slug' => 'restau'
    ]);
  }



}

