<?php

namespace App\Repository;

use App\Entity\Area;
use App\Entity\Entry;
use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Entry|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entry|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entry[]    findAll()
 * @method Entry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Entry::class);
    }

    public function persist(Entry $entry)
    {
        $this->_em->persist($entry);
    }

    public function insert(Entry $entry)
    {
        $this->persist($entry);
        $this->flush();
    }

    public function remove(Entry $entry)
    {
        $this->_em->remove($entry);
    }

    public function flush()
    {
        $this->_em->flush();
    }

    /**
     * @param array $args
     * @return Entry[] Returns an array of Entry objects
     */
    public function search(array $args = [])
    {
        $name = $args['name'] ?? null;
        $area = $args['area'] ?? null;
        $room = $args['room'] ?? null;
        $entryType = $args['entry_type'] ?? null;
        $type = $args['type'] ?? null;

        $qb = $this->createQueryBuilder('entry');

        if ($name) {
            $qb->andWhere('entry.name LIKE :name')
                ->setParameter('name', '%'.$name.'%');
        }
        if ($area instanceof Area) {
            $rooms = $this->getRooms($area);
            $qb->andWhere('entry.roomId IN (:rooms)')
                ->setParameter('room', $rooms);
        }
        if ($room instanceof Room) {
            $qb->andWhere('entry.roomId = :room')
                ->setParameter('room', $room->getId());
        }
        if ($entryType) {
            $qb->andWhere('entry.entryType = :entryType')
                ->setParameter('entryType', $entryType);
        }
        if ($type) {
            $qb->andWhere('entry.type = :type')
                ->setParameter('type', $type);
        }

        return $qb
            ->orderBy('entry.startTime', 'DESC')
            ->setMaxResults(500)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param \DateTimeInterface|null $dateTime
     * @param Area|null $area
     * @param Room|null $room
     * @return Entry[] Returns an array of Entry objects
     */
    public function search2(\DateTimeInterface $dateTime = null, Area $area = null, Room $room = null)
    {
        $qb = $this->createQueryBuilder('entry');

        if ($dateTime) {
            $date = $dateTime->format('Y-m-d');
            $qb->andWhere('entry.startTime LIKE :date')
                ->setParameter('date', '%'.$date.'%');
        }

        if ($area instanceof Area) {
            $rooms = $this->getRooms($area);
            $qb->andWhere('entry.roomId IN (:rooms)')
                ->setParameter('rooms', $rooms);
        }

        if ($room instanceof Room) {
            $qb->andWhere('entry.roomId = :room')
                ->setParameter('room', $room->getId());
        }

        return $qb
            ->orderBy('entry.startTime', 'DESC')
            ->setMaxResults(500)
            ->getQuery()
            ->getResult();
    }

    /**
     * Pour wordpress
     * @return mixed
     */
    public function getBookings()
    {
        return $this->createQueryBuilder('entry')
            ->andWhere('entry.booking IS NOT NULL')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Area $area
     * @return Room[]|iterable
     */
    private function getRooms(Area $area)
    {
        $areaRepository = $this->getEntityManager()->getRepository(Room::class);

        return $areaRepository->findByArea($area);
    }

}
