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

    public function persist(GrrArea $grrArea)
    {
        $this->_em->persist($grrArea);
    }

    public function insert(GrrArea $grrArea)
    {
        $this->persist($grrArea);
        $this->flush();
    }

    public function remove(GrrArea $grrArea)
    {
        $this->_em->remove($grrArea);
    }

    public function flush()
    {
        $this->_em->flush();
    }

    public function ge(GrrArea $area) {
        return $this->createQueryBuilder('area')
            ->andWhere('are');
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
