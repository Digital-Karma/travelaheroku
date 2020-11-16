<?php

namespace App\Repository;

use App\Entity\FocusPays;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FocusPays|null find($id, $lockMode = null, $lockVersion = null)
 * @method FocusPays|null findOneBy(array $criteria, array $orderBy = null)
 * @method FocusPays[]    findAll()
 * @method FocusPays[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FocusPaysRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FocusPays::class);
    }

    public function findBestPays($limit){
        return $this->createQueryBuilder('p')
                    ->select('p as pays, AVG(c.rating) as avgRatings')
                    ->join('p.comments', 'c')
                    ->groupBy('p')
                    ->orderBy('avgRatings', 'DESC')
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();
    }


    // /**
    //  * @return FocusPays[] Returns an array of FocusPays objects
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
    public function findOneBySomeField($value): ?FocusPays
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
