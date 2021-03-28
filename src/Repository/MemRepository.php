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

    public function findByName($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.name = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByUpdatedAt($value): ?Mem
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.updated_at = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findByCreatorId($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.creator = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }
}
