<?php

namespace App\Repository;

use App\Entity\GrrJTypeArea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GrrJTypeArea|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrrJTypeArea|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrrJTypeArea[]    findAll()
 * @method GrrJTypeArea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrrJTypeAreaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GrrJTypeArea::class);
    }

    // /**
    //  * @return GrrJTypeArea[] Returns an array of GrrJTypeArea objects
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
    public function findOneBySomeField($value): ?GrrJTypeArea
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
