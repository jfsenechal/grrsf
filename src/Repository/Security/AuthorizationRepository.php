<?php

namespace App\Repository\Security;

use App\Entity\Area;
use App\Entity\Room;
use App\Entity\Security\User;
use App\Entity\Security\Authorization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Authorization|null find($id, $lockMode = null, $lockVersion = null)
 * @method Authorization|null findOneBy(array $criteria, array $orderBy = null)
 * @method Authorization[]    findAll()
 * @method Authorization[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorizationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Authorization::class);
    }

    /**
     * Pour montrer les droits par area
     * @param Area $area
     * @return Authorization[]
     * @throws \Exception
     */
    public function findByArea(Area $area)
    {
        return $this->findByUserAndArea(null, $area);
    }

    /**
     * Pour montrer les droits par user
     * @param User $user
     * @return Authorization[]
     * @throws \Exception
     */
    public function findByUser(User $user)
    {
        return $this->findByUserAndArea($user, null);
    }

    /**
     * getRoomsUserCanAdd
     * @param User $user
     * @return Authorization[]
     * @throws \Exception
     */
    public function findByUserAndArea(?User $user, ?Area $area)
    {
        if (!$user && !$area) {
            throw new \Exception('At least one parameter is needed');
        }

        $queryBuilder = $this->createQueryBuilder('authorization');

        if ($user) {
            $this->setCriteriaUser($queryBuilder, $user);
        }

        if ($area) {
            $this->setCriteriaArea($queryBuilder, $area);
        }

        return $queryBuilder
            ->addOrderBy('authorization.user', 'ASC')
            ->orderBy('authorization.room', 'ASC')
            ->orderBy('authorization.area', 'ASC')
            ->getQuery()
            ->getResult();
    }

    protected function setCriteriaUser(QueryBuilder $queryBuilder, User $user)
    {
        $queryBuilder->andWhere('authorization.user = :user')
            ->setParameter('user', $user);
    }

    protected function setCriteriaArea(QueryBuilder $queryBuilder, Area $area)
    {
        $repository = $this->getEntityManager()->getRepository(Room::class);
        $rooms = $repository->findByArea($area);
        $queryBuilder->andWhere('authorization.area = :area')
            ->setParameter('area', $area);

        $queryBuilder->orWhere('authorization.room IN (:rooms)')
            ->setParameter('rooms', $rooms);
    }

    /**
     * Pour montrer les droits par room
     * @param Room $room
     * @return Authorization[]
     */
    public function findByRoom(Room $room)
    {
        $queryBuilder = $this->createQueryBuilder('authorization')
            ->andWhere('authorization.room = :room')
            ->setParameter('room', $room);

        return $queryBuilder
            ->addOrderBy('authorization.user', 'ASC')
            ->orderBy('authorization.area', 'ASC')
            ->orderBy('authorization.room', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Utilise dans migration checker
     * @param User $user
     * @return Authorization[]
     */
    public function findByUserAndAreaNotNull(User $user, bool $isAreaAdministrator)
    {
        $queryBuilder = $this->createQueryBuilder('authorization');

        $this->setCriteriaUser($queryBuilder, $user);

        $queryBuilder->andWhere('authorization.area IS NOT NULL');

        $queryBuilder->andWhere('authorization.is_area_administrator = :bool')
            ->setParameter('bool', $isAreaAdministrator);

        return $queryBuilder
            ->addOrderBy('authorization.user', 'ASC')
            ->orderBy('authorization.area', 'ASC')
            ->orderBy('authorization.room', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Utilise dans migration checker
     * @param User $user
     * @param Room $room
     * @return Authorization
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByUserAndRoom(User $user, Room $room)
    {
        $queryBuilder = $this->createQueryBuilder('authorization');

        $this->setCriteriaUser($queryBuilder, $user);

        $queryBuilder->andWhere('authorization.room = :room')
            ->setParameter('room', $room);

        return $queryBuilder->orderBy('authorization.user', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }


}
