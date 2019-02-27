<?php

namespace App\Repository;

use App\Entity\GrrArea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GrrArea|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrrArea|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrrArea[]    findAll()
 * @method GrrArea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrrAreaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GrrArea::class);
    }

    public function getQueryBuilder()
    {
        return $this->createQueryBuilder('grr_area')
            ->orderBy('grr_area.areaName', 'ASC');
    }

    // /**
    //  * @return GrrArea[] Returns an array of GrrArea objects
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
    public function findOneBySomeField($value): ?GrrArea
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
