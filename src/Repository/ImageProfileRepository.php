<?php

namespace App\Repository;

use App\Entity\ImageProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImageProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageProfile[]    findAll()
 * @method ImageProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageProfile::class);
    }

    // /**
    //  * @return ImageProfile[] Returns an array of ImageProfile objects
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
    public function findOneBySomeField($value): ?ImageProfile
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
