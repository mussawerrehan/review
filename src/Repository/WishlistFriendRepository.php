<?php

namespace App\Repository;

use App\Entity\WishlistFriend;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WishlistFriend|null find($id, $lockMode = null, $lockVersion = null)
 * @method WishlistFriend|null findOneBy(array $criteria, array $orderBy = null)
 * @method WishlistFriend[]    findAll()
 * @method WishlistFriend[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WishlistFriendRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WishlistFriend::class);
    }

    // /**
    //  * @return WishlistFriend[] Returns an array of WishlistFriend objects
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
    public function findOneBySomeField($value): ?WishlistFriend
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
