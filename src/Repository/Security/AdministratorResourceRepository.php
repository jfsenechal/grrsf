<?php

namespace App\Repository\Security;

use App\Entity\Security\UserManagerResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserManagerResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserManagerResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserManagerResource[]    findAll()
 * @method UserManagerResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdministratorResourceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserManagerResource::class);
    }

    // /**
    //  * @return AdministratorResource[] Returns an array of AdministratorResource objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdministratorResource
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
