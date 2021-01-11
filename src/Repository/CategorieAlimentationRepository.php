<?php

namespace App\Repository;

use App\Entity\CategorieAlimentation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategorieAlimentation|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieAlimentation|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieAlimentation[]    findAll()
 * @method CategorieAlimentation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieAlimentationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieAlimentation::class);
    }

    // /**
    //  * @return CategorieAlimentation[] Returns an array of CategorieAlimentation objects
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
    public function findOneBySomeField($value): ?CategorieAlimentation
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
