<?php

namespace App\Repository;

use App\Entity\TypeArea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeArea|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeArea|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeArea[]    findAll()
 * @method TypeArea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeAreaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeArea::class);
    }

    public function persist(TypeArea $typeArea)
    {
        $this->_em->persist($typeArea);
    }

    public function insert(TypeArea $typeArea)
    {
        $this->persist($typeArea);
        $this->flush();
    }

    public function remove(TypeArea $typeArea)
    {
        $this->_em->remove($typeArea);
    }

    public function flush()
    {
        $this->_em->flush();
    }

    // /**
    //  * @return TypeArea[] Returns an array of TypeArea objects
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
    public function findOneBySomeField($value): ?TypeArea
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
