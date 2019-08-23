<?php

namespace App\Repository;

use App\Entity\PeriodicityDay;
use App\Entity\Room;
use App\Model\Month;
use Carbon\CarbonInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PeriodicityDay|null find($id, $lockMode = null, $lockVersion = null)
 * @method PeriodicityDay|null findOneBy(array $criteria, array $orderBy = null)
 * @method PeriodicityDay[]    findAll()
 * @method PeriodicityDay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeriodicityDayRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PeriodicityDay::class);
    }

    /**
     * @param Month $monthModel
     * @return PeriodicityDay[]
     */
    public function findForMonth(Month $monthModel, Room $room = null)
    {
        $qb = $this->createQueryBuilder('periodicity_day');
        $qb->leftJoin('periodicity_day.entry', 'entry', 'WITH');
        $qb->addSelect('entry');

        $firstDayImmutable = $monthModel->getFirstDayImmutable();
        $timeString = $firstDayImmutable->format('Y-m').'%';

        $qb->andWhere('periodicity_day.date_periodicity LIKE :time')
            ->setParameter('time', $timeString);

        if ($room !== null) {
            $qb->andWhere('entry.room = :room')
                ->setParameter('room', $room);
        }

        return $qb
            ->orderBy('periodicity_day.date_periodicity', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param CarbonInterface $day
     * @param Room $room
     * @return PeriodicityDay[]
     */
    public function findForDay(CarbonInterface $day, Room $room)
    {
        $qb = $this->createQueryBuilder('periodicity_day');
        $qb->leftJoin('periodicity_day.entry', 'entry', 'WITH');
        $qb->addSelect('entry');

        $qb->andWhere('periodicity_day.date_periodicity LIKE :begin')
            ->setParameter('begin', $day->format('Y-m-d').'%');

        $qb->andWhere('entry.room = :room')
            ->setParameter('room', $room);

        return $qb
            ->orderBy('periodicity_day.date_periodicity', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function persist(PeriodicityDay $periodicityDay)
    {
        $this->_em->persist($periodicityDay);
    }

    public function insert(PeriodicityDay $area)
    {
        $this->persist($area);
        $this->flush();
    }

    public function remove(PeriodicityDay $periodicityDay)
    {
        $this->_em->remove($periodicityDay);
    }

    public function flush()
    {
        $this->_em->flush();
    }
}
