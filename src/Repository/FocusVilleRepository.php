<?php

namespace App\Repository;

use App\Entity\FocusVille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FocusVille|null find($id, $lockMode = null, $lockVersion = null)
 * @method FocusVille|null findOneBy(array $criteria, array $orderBy = null)
 * @method FocusVille[]    findAll()
 * @method FocusVille[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FocusVilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FocusVille::class);
    }

    
    public function findBestVille($limit){
        return $this->createQueryBuilder('v')
                    ->select('v as ville, AVG(c.rating) as avgRatings')
                    ->join('v.comments', 'c')
                    ->groupBy('v')
                    ->orderBy('avgRatings', 'DESC')
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();
    }


    // /**
    //  * @return FocusVille[] Returns an array of FocusVille objects
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
    public function findOneBySomeField($value): ?FocusVille
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
