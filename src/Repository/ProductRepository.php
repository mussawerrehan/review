<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findWithAverage($q)
    {
        $em=$this->getEntityManager();
        $query = $this->createQueryBuilder('p')
            ->Select(array('p.id','p.name','p.image') )// to make Doctrine actually use the join
            ->leftJoin('p.reviews', 'r')
            ->groupBy('p.id')
            ->orderBy('rating','DESC');

        if($q)
        {
            $query->having('avg(r.star) = :val')
            ->setParameter('val',$q);
        }
        $query->addSelect('avg(r.star) AS rating');

//        $count = $this->countQuery($q);
//
//        $query = $this->getEntityManager()
//            ->createQuery($query)
//            ->setHint('knp_paginator.count', $count)
//        ;
        return $query->getQuery()->getResult();
    }

//

    /**
     * @return Product[] Returns an array of Product objects
     */

    public function  findByAverageRating($rating)
    {
        $em = $this->getEntityManager();
        $query = $this->createQueryBuilder('p')
            ->Select(array('p.id','p.name','p.image','avg(r.star) AS rating') )// to make Doctrine actually use the join
            ->leftJoin('p.reviews', 'r')
            ->groupBy('p.id')
            ->orderBy('rating','DESC')
            ->having('avg(r.star) = :val')
            ->setParameter('val',$rating)
            ->getQuery();

        return $query->getResult();
    }

}
