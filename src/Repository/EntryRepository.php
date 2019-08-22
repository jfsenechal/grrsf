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
     * @param Month $monthModel
     * @param Area|null $area
     * @param Room|null $room
     * @return Entry[] Returns an array of Entry objects
     */
    public function findForMonth(Month $monthModel, Area $area = null, Room $room = null)
    {
        $qb = $this->createQueryBuilder('entry');

        $qb->andWhere('entry.start_time LIKE :time')
            ->setParameter(
                'time',
                $monthModel->getFirstDayImmutable()->year.'-'.$monthModel->getFirstDayImmutable()->format('m').'%'
            );

        if ($area !== null) {
            $rooms = $this->getRooms($area);
            $qb->andWhere('entry.room IN (:rooms)')
                ->setParameter('rooms', $rooms);
        }

        if ($room !== null) {
            $qb->andWhere('entry.room = :room')
                ->setParameter('room', $room);
        }

        return $qb
            ->orderBy('entry.start_time', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param CarbonInterface $day
     * @param Room $room
     * @return Entry[]
     */
    public function findByDayAndRoom(CarbonInterface $day, Room $room)
    {
        $qb = $this->createQueryBuilder('entry');

        $qb->andWhere('entry.start_time LIKE :begin')
            ->setParameter('begin', $day->format('Y-m-d').'%');

        $qb->andWhere('entry.room = :room')
            ->setParameter('room', $room);

        return $qb
            ->orderBy('entry.start_time', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     *
     * @param Entry $entry
     * @param Room $room
     * @return Entry[]
     */
    public function isBusy(Entry $entry, Room $room)
    {
        $qb = $this->createQueryBuilder('entry');

        $begin = $entry->getStartTime();
        $end = $entry->getEndTime();

        $qb->andWhere('entry.start_time BETWEEN :begin AND :end')
            ->setParameter('begin', $begin->format('Y-m-d H:i'))
            ->setParameter('end', $end->format('Y-m-d H:i'));

        $qb->orWhere('entry.end_time BETWEEN :begin1 AND :end1')
            ->setParameter('begin1', $begin->format('Y-m-d H:i'))
            ->setParameter('end1', $end->format('Y-m-d H:i'));


        /*  $qb->andWhere('entry.end_time >= :begin OR entry.start_time <= :end ')
              ->setParameter('begin', $begin->format('Y-m-d H:i'))
              ->setParameter('end', $end->format('Y-m-d H:i'));*/

        $qb->andWhere('entry.room = :room')
            ->setParameter('room', $room);

        /**
         * en cas de modif
         */
        if ($entry->getId() !== null) {
            $qb->andWhere('entry.id != :id')
                ->setParameter('id', $entry->getId());
        }

        return $qb
            ->orderBy('entry.start_time', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array $args
     *
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
            ->orderBy('entry.start_time', 'DESC')
            ->setMaxResults(500)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param \DateTimeInterface|null $dateTime
     * @param Area|null $area
     * @param Room|null $room
     *
     * @return Entry[] Returns an array of Entry objects
     */
    public function search2(\DateTimeInterface $dateTime = null, Area $area = null, Room $room = null)
    {
        $qb = $this->createQueryBuilder('entry');

        if ($dateTime !== null) {
            $date = $dateTime->format('Y-m-d');
            $qb->andWhere('entry.start_time LIKE :date')
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
            ->orderBy('entry.start_time', 'DESC')
            ->setMaxResults(500)
            ->getQuery()
            ->getResult();
    }

    /**
     * Pour wordpress.
     *
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
     *
     * @return Room[]|iterable
     */
    private function getRooms(Area $area)
    {
        $roomRepository = $this->getEntityManager()->getRepository(Room::class);

        //   $this->getEntityManager();

        return $roomRepository->findByArea($area);
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
