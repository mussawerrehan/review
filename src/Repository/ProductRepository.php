<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    /**
     * @return Product[] Returns an array of Product objects
     */

    public function findWithAverage()
    {
        $em=$this->getEntityManager();
        $query = $this->createQueryBuilder('p')
            ->Select(array('p.id','p.name','p.image','avg(r.star) AS rating') )// to make Doctrine actually use the join
            ->leftJoin('p.reviews', 'r')
            ->groupBy('p.id')
            ->orderBy('rating','DESC')
            ->getQuery();

        return $query->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
