<?php

namespace App\Repository;

use App\Entity\CategorieCharge;
use App\Entity\Charges;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Charges|null find($id, $lockMode = null, $lockVersion = null)
 * @method Charges|null findOneBy(array $criteria, array $orderBy = null)
 * @method Charges[]    findAll()
 * @method Charges[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChargesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Charges::class);
    }

    // /**
    //  * @return Charges[] Returns an array of Charges objects
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
    public function findOneBySomeField($value): ?Charges
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getAllByMonth($month, $year = null) 
    {    
        if (!$year) {
            $year = date('y');
        }   
        
        $query_date = $year . '-'. $month . '-' . '01';                

        // First day of the month.
        $debut = date('Y-m-01', strtotime($query_date));

        // Last day of the month.
        $fin =  date('Y-m-t', strtotime($query_date));

        return $this->createQueryBuilder('c')
            ->select()
            ->where("c.createdAt >= ?1")
            ->andWhere("c.createdAt <= ?2")
            ->setParameter(1, new \DateTime($debut))
            ->setParameter(2, new \DateTime($fin))
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getAllChargesFixes(CategorieCharge $categorie)
    {
        return $this->createQueryBuilder('c')
            ->select()
            ->where("c.categorie = :categorie")
            ->setParameter('categorie', $categorie)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
