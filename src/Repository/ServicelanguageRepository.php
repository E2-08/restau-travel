<?php

namespace App\Repository;

use App\Entity\Servicelanguage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Servicelanguage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Servicelanguage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Servicelanguage[]    findAll()
 * @method Servicelanguage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServicelanguageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Servicelanguage::class);
    }

//    /**
//     * @return Servicelanguage[] Returns an array of Servicelanguage objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Servicelanguage
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
