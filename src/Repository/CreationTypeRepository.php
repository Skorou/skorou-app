<?php

namespace App\Repository;

use App\Entity\CreationType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CreationType|null find($id, $lockMode = null, $lockVersion = null)
 * @method CreationType|null findOneBy(array $criteria, array $orderBy = null)
 * @method CreationType[]    findAll()
 * @method CreationType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreationTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CreationType::class);
    }

    // /**
    //  * @return CreationType[] Returns an array of CreationType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CreationType
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
