<?php

namespace App\Repository;

use App\Entity\MarkerVille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MarkerVille|null find($id, $lockMode = null, $lockVersion = null)
 * @method MarkerVille|null findOneBy(array $criteria, array $orderBy = null)
 * @method MarkerVille[]    findAll()
 * @method MarkerVille[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarkerVilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MarkerVille::class);
    }

    // /**
    //  * @return MarkerVille[] Returns an array of MarkerVille objects
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
    public function findOneBySomeField($value): ?MarkerVille
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
