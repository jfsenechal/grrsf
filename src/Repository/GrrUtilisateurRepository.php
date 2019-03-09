<?php

namespace App\Repository;

use App\Entity\GrrUtilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GrrUtilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrrUtilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrrUtilisateur[]    findAll()
 * @method GrrUtilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrrUtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GrrUtilisateur::class);
    }

    public function persist(GrrUtilisateur $grrUtilisateur)
    {
        $this->_em->persist($grrUtilisateur);
    }

    public function insert(GrrUtilisateur $grrUtilisateur)
    {
        $this->persist($grrUtilisateur);
        $this->flush();
    }

    public function remove(GrrUtilisateur $grrUtilisateur)
    {
        $this->_em->remove($grrUtilisateur);
    }

    public function flush()
    {
        $this->_em->flush();
    }

    // /**
    //  * @return GrrUtilisateurs[] Returns an array of GrrUtilisateurs objects
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
    public function findOneBySomeField($value): ?GrrUtilisateurs
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
