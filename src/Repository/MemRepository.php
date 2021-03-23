<?php

namespace App\Repository;

use App\Entity\Mem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mem|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mem|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mem[]    findAll()
 * @method Mem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mem::class);
    }

    // /**
    //  * @return Mem[] Returns an array of Mem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Mem
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
