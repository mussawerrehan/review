<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function findOrderedListForUser($user)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :val')
            ->setParameter('val', $user)
            ->orderBy('r.star', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

}
