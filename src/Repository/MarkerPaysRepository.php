<?php

namespace App\Repository;

use App\Entity\MarkerPays;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MarkerPays|null find($id, $lockMode = null, $lockVersion = null)
 * @method MarkerPays|null findOneBy(array $criteria, array $orderBy = null)
 * @method MarkerPays[]    findAll()
 * @method MarkerPays[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarkerPaysRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MarkerPays::class);
    }

    // /**
    //  * @return MarkerPays[] Returns an array of MarkerPays objects
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
    public function findOneBySomeField($value): ?MarkerPays
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
