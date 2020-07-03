<?php

namespace App\Repository;

use App\Entity\UserFont;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserFont|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserFont|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserFont[]    findAll()
 * @method UserFont[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserFontRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFont::class);
    }

    // /**
    //  * @return UserFont[] Returns an array of UserFont objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserFont
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
