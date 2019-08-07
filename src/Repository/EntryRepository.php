<?php

namespace App\Repository;

use App\Entity\Area;
use App\Entity\Entry;
use App\Entity\Room;
use App\Model\Month;
use Carbon\CarbonInterface;
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

    /**
     * @param array $args
     * @return Entry[] Returns an array of Entry objects
     */
    public function findForMonth(Month $monthModel, Area $area = null, Room $room = null)
    {
        $qb = $this->createQueryBuilder('entry');

        $qb->andWhere('YEAR(entry.startTime) = :year')
            ->setParameter('year', $monthModel->getFirstDayImmutable()->year);

        $qb->andWhere('MONTH(entry.startTime) = :month')
            ->setParameter('month', $monthModel->getFirstDayImmutable()->month);

        if ($area) {
            $rooms = $this->getRooms($area);
            $qb->andWhere('entry.room IN (:rooms)')
                ->setParameter('rooms', $rooms);
        }

        if ($room) {
            $qb->andWhere('entry.room = :room')
                ->setParameter('room', $room);
        }

        return $qb
            ->orderBy('entry.startTime', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findForWeek(CarbonInterface $day, Room $room)
    {
        $qb = $this->createQueryBuilder('entry');

        $qb->andWhere('YEAR(entry.startTime) = :year')
            ->setParameter('year', $day->year);

        $qb->andWhere('MONTH(entry.startTime) = :month')
            ->setParameter('month', $day->month);

        $qb->andWhere('DAY(entry.startTime) = :day')
            ->setParameter('day', $day->day);

        $qb->andWhere('entry.room = :room')
            ->setParameter('room', $room);

        return $qb
            ->orderBy('entry.startTime', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findForDay(CarbonInterface $day, Room $room)
    {
        $qb = $this->createQueryBuilder('entry');

        $qb->andWhere('entry.startTime LIKE :begin')
            ->setParameter('begin', $day->format('Y-m-d').'%');

        /* $qb->andWhere('entry.startTime = :year')
             ->setParameter('year', $begin->year);

         $qb->andWhere('MONTH(entry.endTime) = :month')
             ->setParameter('month', $begin->month);*/

        $qb->andWhere('entry.room = :room')
            ->setParameter('room', $room);

        return $qb
            ->orderBy('entry.startTime', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findForDay2(CarbonInterface $begin, CarbonInterface $end, Room $room)
    {
        $qb = $this->createQueryBuilder('entry');

        $qb->where('entry.startTime <= :begin AND entry.endTime >= :end')
            ->setParameter('begin', $begin)
            ->setParameter('end', $end);

        /* $qb->andWhere('entry.startTime = :year')
             ->setParameter('year', $begin->year);

         $qb->andWhere('MONTH(entry.endTime) = :month')
             ->setParameter('month', $begin->month);*/

        $qb->andWhere('entry.room = :room')
            ->setParameter('room', $room);

        return $qb
            ->orderBy('entry.startTime', 'DESC')
            ->getQuery()
            ->getResult();
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
            $qb->andWhere('entry.room IN (:rooms)')
                ->setParameter('room', $rooms);
        }
        if ($room instanceof Room) {
            $qb->andWhere('entry.room = :room')
                ->setParameter('room', $room);
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
                ->setParameter('room', $room);
        }

        return $qb
            ->orderBy('entry.startTime', 'DESC')
            ->setMaxResults(500)
            ->getQuery()
            ->getResult();
    }

    /**
     * Pour wordpress
     * @return Entry[]
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

        $this->getEntityManager();
        return $areaRepository->findByArea($area);
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


}
