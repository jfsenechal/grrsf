<?php

namespace App\Repository;

use App\Entity\Area;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Area|null find($id, $lockMode = null, $lockVersion = null)
 * @method Area|null findOneBy(array $criteria, array $orderBy = null)
 * @method Area[]    findAll()
 * @method Area[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AreaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Area::class);
    }

    public function getQueryBuilder()
    {
        return $this->createQueryBuilder('area')
            ->orderBy('area.areaName', 'ASC');
    }

    public function persist(Area $grrArea)
    {
        $this->_em->persist($grrArea);
    }

    public function insert(Area $grrArea)
    {
        $this->persist($grrArea);
        $this->flush();
    }

    public function remove(Area $grrArea)
    {
        $this->_em->remove($grrArea);
    }

    public function flush()
    {
        $this->_em->flush();
    }

    public function ge(Area $area) {
        return $this->createQueryBuilder('area')
            ->andWhere('are');
    }



    // /**
    //  * @return Area[] Returns an array of Area objects
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
    public function findOneBySomeField($value): ?Area
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
