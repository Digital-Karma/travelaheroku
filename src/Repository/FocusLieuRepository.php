<?php

namespace App\Repository;

use App\Entity\FocusLieu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FocusLieu|null find($id, $lockMode = null, $lockVersion = null)
 * @method FocusLieu|null findOneBy(array $criteria, array $orderBy = null)
 * @method FocusLieu[]    findAll()
 * @method FocusLieu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FocusLieuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FocusLieu::class);
    }

    
    public function findBestLieu($limit){
        return $this->createQueryBuilder('l')
                    ->select('l as lieu, AVG(c.rating) as avgRatings')
                    ->join('l.comments', 'c')
                    ->groupBy('l')
                    ->orderBy('avgRatings', 'DESC')
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();
    }


    // /**
    //  * @return FocusLieu[] Returns an array of FocusLieu objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FocusLieu
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
