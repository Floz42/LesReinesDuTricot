<?php

namespace App\Repository;

use App\Entity\TokenUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TokenUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method TokenUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method TokenUser[]    findAll()
 * @method TokenUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TokenUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TokenUser::class);
    }

    // /**
    //  * @return TokenUser[] Returns an array of TokenUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TokenUser
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
