<?php

namespace App\Repository;

use App\Entity\GrrRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GrrRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrrRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrrRoom[]    findAll()
 * @method GrrRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrrRoomRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GrrRoom::class);
    }

    public function getForSearch()
    {
        return $this->createQueryBuilder('grr_room')
            ->orderBy('grr_room.roomName', 'ASC');
    }

    // /**
    //  * @return GrrRoom[] Returns an array of GrrRoom objects
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
    public function findOneBySomeField($value): ?GrrRoom
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
