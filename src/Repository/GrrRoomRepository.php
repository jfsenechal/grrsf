<?php

namespace App\Repository;

use App\Entity\GrrArea;
use App\Entity\GrrRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GrrRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrrRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrrRoom[]    findAll()
 * @method GrrRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrrRoomRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GrrRoom::class);
    }

    public function getQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('grr_room')
            ->orderBy('grr_room.roomName', 'ASC');
    }

    public function getRoomsByAreaQueryBuilder(GrrArea $area): QueryBuilder
    {
        $qb = $this->createQueryBuilder('grr_room')
            ->andWhere('grr_room.areaId = :area')->setParameter('area', $area->getId())
            ->orderBy('grr_room.roomName', 'ASC');

        return $qb;
    }

    public function persist(GrrRoom $grrRoom)
    {
        $this->_em->persist($grrRoom);
    }

    public function insert(GrrRoom $grrRoom)
    {
        $this->persist($grrRoom);
        $this->flush();
    }

    public function remove(GrrRoom $grrRoom)
    {
        $this->_em->remove($grrRoom);
    }

    public function flush()
    {
        $this->_em->flush();
    }

    /**
     * @return GrrRoom[] Returns an array of GrrRoom objects
     */
    public function findByArea(GrrArea $area): iterable
    {
        return $this->createQueryBuilder('grr_room')
            ->andWhere('grr_room.areaId = :area')
            ->setParameter('area', $area->getId())
            ->orderBy('grr_room.roomName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getForForm(): iterable
    {
        $result = $this->createQueryBuilder('grr_room')
            ->orderBy('grr_room.roomName', 'ASC')
            ->getQuery()
            ->getResult();

        $rooms = [];
        foreach ($result as $romm) {
            $rooms[$romm->getRoomName()] = $romm->getId();
        }

        return $rooms;

    }

    /*
    public function findOneBySomeField($value): ?GrrRoom
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
