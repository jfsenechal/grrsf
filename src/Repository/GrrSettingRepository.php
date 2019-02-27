<?php

namespace App\Repository;

use App\Entity\GrrSetting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GrrSetting|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrrSetting|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrrSetting[]    findAll()
 * @method GrrSetting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrrSettingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GrrSetting::class);
    }

    // /**
    //  * @return GrrSetting[] Returns an array of GrrSetting objects
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
    public function findOneBySomeField($value): ?GrrSetting
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
