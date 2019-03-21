<?php

namespace App\Repository;

use App\Entity\Repeat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Repeat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Repeat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Repeat[]    findAll()
 * @method Repeat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepeatRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Repeat::class);
    }

    public function persist(Repeat $grrRepeat)
    {
        $this->_em->persist($grrRepeat);
    }

    public function insert(Repeat $grrRepeat)
    {
        $this->persist($grrRepeat);
        $this->flush();
    }

    public function remove(Repeat $grrRepeat)
    {
        $this->_em->remove($grrRepeat);
    }

    public function flush()
    {
        $this->_em->flush();
    }

    
    /**
     * @return Repeat[] Returns an array of Repeat objects
     */
    public function search()
    {
        return $this->createQueryBuilder('repeat')
            ->orderBy('repeat.startTime', 'DESC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Repeat
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
