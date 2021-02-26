<?php

namespace App\Repository;

use App\Entity\WhishlistItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WhishlistItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method WhishlistItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method WhishlistItem[]    findAll()
 * @method WhishlistItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WhishlistItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WhishlistItem::class);
    }

    // /**
    //  * @return WhishlistItem[] Returns an array of WhishlistItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WhishlistItem
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
