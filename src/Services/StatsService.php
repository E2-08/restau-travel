<?php
namespace App\Services;

use Doctrine\Common\Persistence\ObjectManager;


class StatsService
{

    private $manager;
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }


    public function getEntityCount(string $entityName, array $condition = [])
    {
        return $this->manager->createQuery($this->getQuery($entityName, $condition))->getSingleScalarResult();
    }

    /**
     * Factorisation 
     *
     * @param string $entity
     * @param array $cond
     * @return string
     */
    private function getQuery(string $entity, array $cond = []) : string
    {
        $sql = 'SELECT COUNT(b) FROM App\Entity\\' . $entity . ' b';
        $i = 0;
        if (!empty($cond)) {
            $sql = $sql . ' where ';

            foreach ($cond as $key => $value) {
                $sql = $sql . ' b.' . $key . ' = ' . $value;
                $i = $i + 1;
                if ($i < count($cond)) {
                    $sql = $sql . ' and';
                }
            }
        }
        return $sql;
    }


}