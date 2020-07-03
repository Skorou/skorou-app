<?php

namespace App\Repository;

use App\Entity\ImageUploaded;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImageUploaded|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageUploaded|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageUploaded[]    findAll()
 * @method ImageUploaded[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageUploadedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageUploaded::class);
    }

    // /**
    //  * @return ImageUploaded[] Returns an array of ImageUploaded objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ImageUploaded
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
