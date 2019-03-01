<?php

namespace App\Repository;

use App\Entity\GrrEntry;
use App\Entity\GrrRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GrrEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrrEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrrEntry[]    findAll()
 * @method GrrEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrrEntryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GrrEntry::class);
    }


    public function flush()
    {
        $this->_em->flush();
    }

    public function remove(GrrEntry $grrEntry)
    {
        $this->_em->remove($grrEntry);
    }

    public function persist(GrrEntry $grrEntry)
    {
        $this->_em->persist($grrEntry);
    }

    public function insert(GrrEntry $grrEntry)
    {
        $this->persist($grrEntry);
        $this->flush();
    }

    /**
     * @param array $args
     * @return GrrEntry[] Returns an array of GrrEntry objects
     */
    public function search(array $args = [])
    {
        $name = $args['name'] ?? null;
        $room = $args['room'] ?? null;
        $entryType = $args['entry_type'] ?? null;
        $type = $args['type'] ?? null;

        $qb = $this->createQueryBuilder('grr_entry');

        if ($name) {
            $qb->andWhere('grr_entry.name LIKE :name')
                ->setParameter('name', '%'.$name.'%');
        }
        if ($room instanceof GrrRoom) {
            $qb->andWhere('grr_entry.roomId = :room')
                ->setParameter('room', $room->getId());
        }
        if ($entryType) {
            $qb->andWhere('grr_entry.entryType = :entryType')
                ->setParameter('entryType', $entryType);
        }
        if ($type) {
            $qb->andWhere('grr_entry.type = :type')
                ->setParameter('type', $type);
        }

        return $qb
            ->orderBy('grr_entry.startTime', 'DESC')
            ->setMaxResults(500)
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?GrrEntry
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
