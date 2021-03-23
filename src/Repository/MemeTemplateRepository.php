<?php

namespace App\Repository;

use App\Entity\MemeTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MemeTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method MemeTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method MemeTemplate[]    findAll()
 * @method MemeTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemeTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemeTemplate::class);
    }

    // /**
    //  * @return MemeTemplate[] Returns an array of MemeTemplate objects
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
    public function findOneBySomeField($value): ?MemeTemplate
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
