<?php

namespace App\Repository;

use App\Entity\Language;
use App\Entity\Restaurant;
use App\Entity\PropertySearch;
use App\Repository\LanguageRepository;
use App\Interfaces\InterfaceRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Egulias\EmailValidator\Exception\ExpectingATEXT;

/**
 * @method Restaurant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Restaurant|null findOneBy($slug,array $criteria, array $orderBy = null)
 * @method Restaurant[]    findAll()
 * @method Restaurant[]    findByLanguages()
 * @method Restaurant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ObjectManager $manager, RegistryInterface $registry)
    {
        parent::__construct($registry, Restaurant::class);
        $this->manager = $manager;

    }


//    /**
//     * @return Restaurant[] Returns an array of Restaurant objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
     */

    /*
    public function findOneBySomeField($value): ?Restaurant
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
     */



    /**
     * Undocumented function
     *
     * @param LanguageRepository $repo
     * @param Request $request
     * @return Retaurant[]|null
     */
    public function search(PropertySearch $search, $limit, $offset)
    {

        $query = $this->createQueryBuilder('r');

        try {

            if ($search->getLanguage() != null) {
                $language = new Language();
                $repo = $this->manager->getRepository(Language::class);
                $language = $repo->findSearchLanguage($search->getLanguage());

                $query = $query->select('r')
                    ->leftJoin('r.languages', 'c')
                    ->addSelect('c')
                    ->add('where', $query->expr()->in('c', ':c'))
                    ->setParameter('c', $language);
            }
            if ($search->getCity() != null) {
                $query = $query->andWhere('r.city like :val')
                    ->setParameter('val', $search->getCity() . '%');
            }

            $query = $query->getQuery()
                ->getResult();
            return $query;

        } catch (Expectin $e) {
            throw new Exception("Error: aucun résultats trouvé");
        }

    }
} 
