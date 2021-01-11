<?php

namespace App\Repository;

use App\Entity\CategorieCharge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategorieCharge|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieCharge|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieCharge[]    findAll()
 * @method CategorieCharge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieChargeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieCharge::class);
    }

    // /**
    //  * @return CategorieCharge[] Returns an array of CategorieCharge objects
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
    public function findOneBySomeField($value): ?CategorieCharge
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
