<?php

namespace App\Repository;

use App\Entity\Setting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Setting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Setting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Setting[]    findAll()
 * @method Setting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SettingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Setting::class);
    }

    public function persist(Setting $grrSetting)
    {
        $this->_em->persist($grrSetting);
    }

    public function remove(Setting $grrSetting)
    {
        $this->_em->remove($grrSetting);
    }

    public function flush()
    {
        $this->_em->flush();
    }

    /**
     * @param string $name
     * @return Setting|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getValueByName(string $name)
    {
        return $this->createQueryBuilder('setting')
            ->andWhere('setting.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
