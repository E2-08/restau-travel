<?php
namespace App\Services;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;


class LanguageService
{
    private $manager;
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }


    public function getLangue(LanguageRepository $repoLanguage)
    {
        return $repoLanguage->findAll();

    }
}