<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class DoctrineHelper
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function AddToDb($code)
    {
        $this->em->persist($code);
        $this->em->flush();
    }
    public function DeleteFromDb($code)
    {
        $this->em->remove($code);
        $this->em->flush();
    }
}