<?php

namespace App\Repository;

use App\Entity\GrrRepeat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GrrRepeat|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrrRepeat|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrrRepeat[]    findAll()
 * @method GrrRepeat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrrRepeatRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GrrRepeat::class);
    }

    /**
     * @return GrrRepeat[] Returns an array of GrrRepeat objects
     */
    public function search()
    {
        return $this->createQueryBuilder('grr_repeat')
            ->orderBy('grr_repeat.startTime', 'DESC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?GrrRepeat
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
