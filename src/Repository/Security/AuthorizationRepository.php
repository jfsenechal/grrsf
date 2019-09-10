<?php

namespace App\Repository\Security;

use App\Entity\Area;
use App\Entity\Room;
use App\Entity\Security\User;
use App\Entity\Security\UserAuthorization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserAuthorization|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAuthorization|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAuthorization[]    findAll()
 * @method UserAuthorization[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorizationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserAuthorization::class);
    }

    /**
     * @param Area $area
     * @return UserAuthorization[]
     */
    public function findByArea(Area $area)
    {
        $queryBuilder = $this->createQueryBuilder('authorization')
            ->orWhere('authorization.area = :area')
            ->setParameter('area', $area);

        $repository = $this->getEntityManager()->getRepository(Room::class);
        $rooms = $repository->findByArea($area);

        $queryBuilder->orWhere('authorization.room IN (:rooms)')
            ->setParameter('rooms', $rooms);

        return $queryBuilder->orderBy('authorization.user', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByUser(User $user)
    {
        $queryBuilder = $this->createQueryBuilder('authorization')
            ->andWhere('authorization.user = :user')
            ->setParameter('user', $user);

        return $queryBuilder->orderBy('authorization.user', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByRoom(Room $room)
    {
        $queryBuilder = $this->createQueryBuilder('authorization')
            ->andWhere('authorization.room = :room')
            ->setParameter('room', $room);

        return $queryBuilder->orderBy('authorization.user', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @return UserAuthorization[]
     */
    public function findByUserAndAreaNotNull(User $user)
    {
        $queryBuilder = $this->createQueryBuilder('authorization');

        $queryBuilder->andWhere('authorization.user = :user')
            ->setParameter('user', $user);

        $queryBuilder->andWhere('authorization.area IS NOT NULL');

        return $queryBuilder->orderBy('authorization.user', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @param Room $room
     * @return UserAuthorization
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByUserAndRoom(User $user, Room $room)
    {
        $queryBuilder = $this->createQueryBuilder('authorization');

        $queryBuilder->andWhere('authorization.user = :user')
            ->setParameter('user', $user);

        $queryBuilder->andWhere('authorization.room = :room')
            ->setParameter('room', $room);

        return $queryBuilder->orderBy('authorization.user', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function persist(UserAuthorization $userAuthorization)
    {
        $this->_em->persist($userAuthorization);
    }

    public function insert(UserAuthorization $userAuthorization)
    {
        $this->persist($userAuthorization);
        $this->flush();
    }

    public function remove(UserAuthorization $userAuthorization)
    {
        $this->_em->remove($userAuthorization);
    }

    public function flush()
    {
        $this->_em->flush();
    }
}
