<?php

namespace App\Repository;

use App\Entity\GrrJUserRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GrrJUserRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrrJUserRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrrJUserRoom[]    findAll()
 * @method GrrJUserRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrrJUserRoomRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GrrJUserRoom::class);
    }

    // /**
    //  * @return GrrJUserRoom[] Returns an array of GrrJUserRoom objects
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
    public function findOneBySomeField($value): ?GrrJUserRoom
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
