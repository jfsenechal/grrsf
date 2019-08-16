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
class UserManagerResourceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserManagerResource::class);
    }

    public function persist(UserManagerResource $userManagerResource)
    {
        $this->_em->persist($userManagerResource);
    }

    public function insert(UserManagerResource $userManagerResource)
    {
        $this->persist($userManagerResource);
        $this->flush();
    }

    public function remove(UserManagerResource $userManagerResource)
    {
        $this->_em->remove($userManagerResource);
    }

    public function flush()
    {
        $this->_em->flush();
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
