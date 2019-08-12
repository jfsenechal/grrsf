<?php

namespace App\Repository;

use App\Entity\Periodicity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Periodicity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Periodicity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Periodicity[]    findAll()
 * @method Periodicity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeriodicityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Periodicity::class);
    }

    public function persist(Periodicity $Repeat)
    {
        $this->_em->persist($Repeat);
    }

    public function insert(Periodicity $Repeat)
    {
        $this->persist($Repeat);
        $this->flush();
    }

    public function remove(Periodicity $Repeat)
    {
        $this->_em->remove($Repeat);
    }

    public function flush()
    {
        $this->_em->flush();
    }

    // /**
    //  * @return Periodicity[] Returns an array of Periodicity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Periodicity
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
