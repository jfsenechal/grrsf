<?php

namespace App\Repository;

use App\Entity\Area;
use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function getQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('room')
            ->orderBy('room.name', 'ASC');
    }

    public function getRoomsByAreaQueryBuilder(Area $area): QueryBuilder
    {
        $qb = $this->createQueryBuilder('room')
            ->andWhere('room.area = :area')
            ->setParameter('area', $area)
            ->orderBy('room.name', 'ASC');

        return $qb;
    }

    public function persist(Room $room)
    {
        $this->_em->persist($room);
    }

    public function insert(Room $room)
    {
        $this->persist($room);
        $this->flush();
    }

    public function remove(Room $room)
    {
        $this->_em->remove($room);
    }

    public function flush()
    {
        $this->_em->flush();
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


    /*
    public function findOneBySomeField($value): ?Room
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