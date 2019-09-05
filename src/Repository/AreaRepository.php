<?php

namespace App\Repository;

use App\Entity\Area;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Area|null find($id, $lockMode = null, $lockVersion = null)
 * @method Area|null findOneBy(array $criteria, array $orderBy = null)
 * @method Area[]    findAll()
 * @method Area[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AreaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Area::class);
    }

    public function getQueryBuilder()
    {
        return $this->createQueryBuilder('area')
            ->orderBy('area.name', 'ASC');
    }

    public function persist(Area $area)
    {
        $this->_em->persist($area);
    }

    public function insert(Area $area)
    {
        $this->persist($area);
        $this->flush();
    }

    public function remove(Area $area)
    {
        $this->_em->remove($area);
    }

    public function flush()
    {
        $this->_em->flush();
    }

}
