<?php

namespace App\Repository\Security;

use App\Entity\Security\ManagerArea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ManagerArea|null find($id, $lockMode = null, $lockVersion = null)
 * @method ManagerArea|null findOneBy(array $criteria, array $orderBy = null)
 * @method ManagerArea[]    findAll()
 * @method ManagerArea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ManagerAreaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ManagerArea::class);
    }

    // /**
    //  * @return ManagerArea[] Returns an array of ManagerArea objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ManagerArea
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
