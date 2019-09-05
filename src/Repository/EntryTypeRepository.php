<?php

namespace App\Repository;

use App\Entity\EntryType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EntryType|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntryType|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntryType[]    findAll()
 * @method EntryType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntryTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EntryType::class);
    }

    public function persist(EntryType $typeArea)
    {
        $this->_em->persist($typeArea);
    }

    public function insert(EntryType $typeArea)
    {
        $this->persist($typeArea);
        $this->flush();
    }

    public function remove(EntryType $typeArea)
    {
        $this->_em->remove($typeArea);
    }

    public function flush()
    {
        $this->_em->flush();
    }
}
