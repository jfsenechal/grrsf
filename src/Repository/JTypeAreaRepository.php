<?php

namespace App\Repository;

use App\Entity\JTypeArea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JTypeArea|null find($id, $lockMode = null, $lockVersion = null)
 * @method JTypeArea|null findOneBy(array $criteria, array $orderBy = null)
 * @method JTypeArea[]    findAll()
 * @method JTypeArea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JTypeAreaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JTypeArea::class);
    }

    // /**
    //  * @return JTypeArea[] Returns an array of JTypeArea objects
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
    public function findOneBySomeField($value): ?JTypeArea
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
