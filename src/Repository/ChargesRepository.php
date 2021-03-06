<?php

namespace App\Repository;

use App\Entity\CategorieCharge;
use App\Entity\Charges;
use App\Entity\User;
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

    public function getAllChargesVariablesByMonth(CategorieCharge $categorie, User $user, $month = null, $year = null) 
    {
        $return = $this->createQueryBuilder('c')
            ->select()
            ->where("c.categorie = :categorie") 
            ->andWhere("c.user = :user")            
            ->setParameter('categorie', $categorie)
            ->setParameter('user', $user)
        ;   

        if ($month) {            
            if (!$year) {
                $year = date('y');
            }
            $query_date = $year . '-'. $month . '-' . '01';
            // First day of the month.
            $debut = date('Y-m-01', strtotime($query_date));
            // Last day of the month.
            $fin =  date('Y-m-t', strtotime($query_date));
            $return = $return->andWhere("c.updatedAt >= ?1")
                    ->andWhere("c.updatedAt <= ?2")            
                    ->setParameter(1, new \DateTime($debut))
                    ->setParameter(2, new \DateTime($fin))
            ;
        }

        $return = $return->orderBy('c.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;

        return $return;
    }

    public function getAllChargesFixes(CategorieCharge $categorie, User $user)
    {
        return $this->createQueryBuilder('c')
            ->select()
            ->where("c.categorie = :categorie")
            ->andWhere("c.user = :user")  
            ->setParameter('categorie', $categorie)
            ->setParameter('user', $user)
            ->orderBy('c.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
