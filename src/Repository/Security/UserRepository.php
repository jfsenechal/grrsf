<?php

namespace App\Repository\Security;

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

    public function getQueryBuilder()
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
    public function search()
    {
        return $this->createQueryBuilder('user')
            ->orderBy('user.name', 'ASC')
            ->getQuery()->getResult();
    }

    public function persist(User $Utilisateur)
    {
        $this->_em->persist($Utilisateur);
    }

    public function insert(User $Utilisateur)
    {
        $this->persist($Utilisateur);
        $this->flush();
    }

    public function remove(User $Utilisateur)
    {
        $this->_em->remove($Utilisateur);
    }

    public function flush()
    {
        $this->_em->flush();
    }

}
