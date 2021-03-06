<?php

namespace App\Repository;

use App\Entity\Alimentation;
use App\Entity\User;
use App\Entity\CategorieAlimentation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Alimentation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Alimentation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Alimentation[]    findAll()
 * @method Alimentation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlimentationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Alimentation::class);
    }

    // /**
    //  * @return Alimentation[] Returns an array of Alimentation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Alimentation
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getAllByMonth(User $user, $month, $year = null) 
    {    
        if (!$year) {
            $year = date('y');
        }   
        
        $query_date = $year . '-'. $month . '-' . '01';                

        // First day of the month.
        $debut = date('Y-m-01', strtotime($query_date));

        // Last day of the month.
        $fin =  date('Y-m-t', strtotime($query_date));

        return $this->createQueryBuilder('a')
            ->select()
            ->where("a.updatedAt >= ?1")
            ->andWhere("a.updatedAt <= ?2")       
            ->andWhere("a.user = ?3")     
            ->setParameter(1, new \DateTime($debut))
            ->setParameter(2, new \DateTime($fin))
            ->setParameter(3, $user)
            ->orderBy('a.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
