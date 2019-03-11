<?php

namespace App\Repository;

use App\Entity\GrrTypeArea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GrrTypeArea|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrrTypeArea|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrrTypeArea[]    findAll()
 * @method GrrTypeArea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrrTypeAreaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GrrTypeArea::class);
    }

    public function persist(GrrTypeArea $typeArea)
    {
        $this->_em->persist($typeArea);
    }

    public function insert(GrrTypeArea $typeArea)
    {
        $this->persist($typeArea);
        $this->flush();
    }

    public function remove(GrrTypeArea $typeArea)
    {
        $this->_em->remove($typeArea);
    }

    public function flush()
    {
        $this->_em->flush();
    }

    // /**
    //  * @return GrrTypeArea[] Returns an array of GrrTypeArea objects
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
    public function findOneBySomeField($value): ?GrrTypeArea
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
