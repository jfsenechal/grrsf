<?php

namespace App\Repository\Security;

use Doctrine\ORM\QueryBuilder;
use App\Entity\Security\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('user')
            ->orderBy('user.name', 'ASC');
    }

    public function loadByUserNameOrEmail(string $username)
    {
        return $this->createQueryBuilder('user')
            ->andWhere('user.email = :username')
            ->orWhere('user.username = :username')
            ->setParameter('username', $username)
            ->orderBy('user.name', 'ASC')
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * @return User[]
     */
    public function search(array $args): array
    {
        $qb = $this->createQueryBuilder('user')
            ->orderBy('user.name', 'ASC');

        $name = $args['name'] ?? null;
        if ($name) {
            $qb->andWhere('user.email LIKE :name OR user.name LIKE :name OR user.username LIKE :name')
                ->setParameter('name', '%'.$name.'%');
        }

        return $qb->getQuery()->getResult();
    }
}
