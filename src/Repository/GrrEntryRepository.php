<?php

namespace App\Repository;

use App\Entity\GrrEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GrrEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrrEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrrEntry[]    findAll()
 * @method GrrEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrrEntryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GrrEntry::class);
    }

    /**
     * @return GrrEntry[] Returns an array of GrrEntry objects
     */
    public function search()
    {
        return $this->createQueryBuilder('grr_entry')
            ->orderBy('grr_entry.startTime', 'DESC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?GrrEntry
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
