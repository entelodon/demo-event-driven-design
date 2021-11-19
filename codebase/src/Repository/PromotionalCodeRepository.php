<?php

namespace App\Repository;

use App\Entity\PromotionalCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PromotionalCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method PromotionalCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method PromotionalCode[]    findAll()
 * @method PromotionalCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionalCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PromotionalCode::class);
    }

    // /**
    //  * @return PromotionalCode[] Returns an array of PromotionalCode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PromotionalCode
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
