<?php

namespace App\Repository;

use App\Entity\UserRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRoom[]    findAll()
 * @method UserRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRoomRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserRoom::class);
    }

    // /**
    //  * @return UserRoom[] Returns an array of UserRoom objects
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
    public function findOneBySomeField($value): ?UserRoom
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
