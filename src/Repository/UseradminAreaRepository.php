<?php

namespace App\Repository;

use App\Entity\UseradminArea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UseradminArea|null find($id, $lockMode = null, $lockVersion = null)
 * @method UseradminArea|null findOneBy(array $criteria, array $orderBy = null)
 * @method UseradminArea[]    findAll()
 * @method UseradminArea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UseradminAreaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UseradminArea::class);
    }

    // /**
    //  * @return UseradminArea[] Returns an array of UseradminArea objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UseradminArea
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
