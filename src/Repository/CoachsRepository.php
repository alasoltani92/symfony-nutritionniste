<?php

namespace App\Repository;

use App\Entity\Coachs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Coachs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Coachs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Coachs[]    findAll()
 * @method Coachs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoachsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coachs::class);
    }



    // /**
    //  * @return Coachs[] Returns an array of Coachs objects
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
    public function findOneBySomeField($value): ?Coachs
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
