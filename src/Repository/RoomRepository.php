<?php

namespace App\Repository;

use App\Entity\Area;
use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function getRoomsByAreaQueryBuilder(Area $area): QueryBuilder
    {
        return $this->createQueryBuilder('room')
            ->andWhere('room.area = :area')
            ->setParameter('area', $area)
            ->orderBy('room.name', 'ASC');
    }

    public function getQueryBuilderEmpty(): QueryBuilder
    {
        return $this->createQueryBuilder('room')
            ->andWhere('room.area = :id')
            ->setParameter('id', 99999)
            ->orderBy('room.name', 'ASC');
    }

    /**
     * @return Room[] Returns an array of Room objects
     */
    public function findByArea(Area $area): iterable
    {
        return $this->createQueryBuilder('room')
            ->andWhere('room.area = :area')
            ->setParameter('area', $area)
            ->orderBy('room.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
