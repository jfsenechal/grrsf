<?php

namespace App\Repository;

use App\Entity\Area;
use App\Entity\Entry;
use App\Entity\Periodicity;
use App\Entity\Room;
use App\Model\Month;
use Carbon\CarbonInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Webmozart\Assert\Assert;

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
     * @param \DateTimeInterface $date
     * @param Area|null $area
     * @param Room|null $room
     *
     * @return Entry[] Returns an array of Entry objects
     */
    public function findForMonth(\DateTimeInterface $date, Area $area, Room $room = null)
    {
        $qb = $this->createQueryBuilder('entry');
        $end = clone $date;
        $end->modify('last day of this month');

        $qb->andWhere('DATE(entry.start_time) >= :begin AND DATE(entry.end_time) <= :end')
            ->setParameter('begin', $date->format('Y-m-d'))
            ->setParameter('end', $end->format('Y-m-d'));

        if (null !== $room) {
            $qb->andWhere('entry.room = :room')
                ->setParameter('room', $room);
        } else {
            $rooms = $this->getRooms($area);
            $qb->andWhere('entry.room IN (:rooms)')
                ->setParameter('rooms', $rooms);
        }

        return $qb
            ->orderBy('entry.start_time', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param CarbonInterface $day
     * @param Room $room
     *
     * @return Entry[]
     */
    public function findForDay(CarbonInterface $day, Room $room)
    {
        $qb = $this->createQueryBuilder('entry');

        $qb->andWhere('DATE(entry.start_time) <= :date AND DATE(entry.end_time) >= :date')
            ->setParameter('date', $day->format('Y-m-d'));

        $qb->andWhere('entry.room = :room')
            ->setParameter('room', $room);

        return $qb
            ->orderBy('entry.start_time', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Entry $entry
     * @param Room $room
     *
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

        $qb->andWhere('entry.room = :room')
            ->setParameter('room', $room);

        /*
         * en cas de modif
         */
        if (null !== $entry->getId()) {
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
                ->setParameter('rooms', $rooms);
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
     * @return Entry[]
     */
    public function withPeriodicity()
    {
        $qb = $this->createQueryBuilder('entry');

        $qb->andWhere('entry.periodicity IS NOT NULL');

        return $qb
            ->orderBy('entry.start_time', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Entry[]
     */
    public function findByPeriodicity(Periodicity $periodicity)
    {
        $qb = $this->createQueryBuilder('entry');

        $qb->andWhere('entry.periodicity = :periodicity')
            ->setParameter('periodicity', $periodicity);

        return $qb
            ->orderBy('entry.start_time', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Ajouter pour sqlLite
     * @param Area $area
     *
     * @return Room[]|iterable
     */
    private function getRooms(Area $area)
    {
        $roomRepository = $this->getEntityManager()->getRepository(Room::class);

        return $roomRepository->findByArea($area);
    }

    /**
     * Retourne l'entry de base de la repetition
     * @param Periodicity $periodicity
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getBaseEntryForPeriodicity(Periodicity $periodicity)
    {
        $qb = $this->createQueryBuilder('entry');

        $qb->andWhere('entry.periodicity = :periodicity')
            ->setParameter('periodicity', $periodicity)
            ->orderBy('entry.start_time', 'ASC');

        return $qb->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

    }

    public function findPeriodicityEntry(Entry $entry): ?Entry
    {
        $periodicity = $entry->getPeriodicity();
        Assert::notNull($periodicity);

        $qb = $this->createQueryBuilder('entry');

        $qb->andWhere('entry.start_time = :start')
            ->setParameter('start', $entry->getStartTime());

        $qb->andWhere('entry.end_time = :end')
            ->setParameter('end', $entry->getEndTime());

        $qb->andWhere('entry.periodicity = :periodicity')
            ->setParameter('periodicity', $periodicity);

        return $qb
            ->orderBy('entry.start_time', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }

}
