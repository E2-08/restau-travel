<?php
namespace App\Services;

use Doctrine\Common\Persistence\ObjectManager;

class PaginationGenerator
{

  private $entityClass;
  private $limit = 10;
  private $currentPage = 1;
  private $manager;


  public function __construct(ObjectManager $manager)
  {
    $this->manager = $manager;
  }

  public function getData()
  {

    $offset = $this->currentPage * $this->limit - $this->limit;
    $repo = $this->manager->getRepository($this->entityClass);

    return $repo->findBy([], [], $this->limit, $offset);
  }
  /**
   * Get the value of entityClass
   */
  public function getEntityClass()
  {
    return $this->entityClass;
  }

  /**
   * Set the value of entityClass
   *
   * @return  self
   */
  public function setEntityClass($entityClass)
  {
    $this->entityClass = $entityClass;

    return $this;
  }

  /**
   * Get the value of limit
   */
  public function getLimit()
  {
    return $this->limit;
  }

  /**
   * Set the value of limit
   *
   * @return  self
   */
  public function setLimit($limit)
  {
    $this->limit = $limit;

    return $this;
  }

  /**
   * Get the value of currentPage
   */
  public function getCurrentPage()
  {
    return $this->currentPage;
  }

  /**
   * Set the value of currentPage
   *
   * @return  self
   */
  public function setCurrentPage($currentPage)
  {
    $this->currentPage = $currentPage;

    return $this;
  }
}