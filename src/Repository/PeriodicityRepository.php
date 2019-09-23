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
}
