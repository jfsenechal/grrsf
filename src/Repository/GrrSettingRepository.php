<?php

namespace App\Repository;

use App\Entity\GrrSetting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GrrSetting|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrrSetting|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrrSetting[]    findAll()
 * @method GrrSetting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrrSettingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GrrSetting::class);
    }

    /**
     * @param string $name
     * @return GrrSetting|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getValueByName(string $name)
    {
        return $this->createQueryBuilder('grr_setting')
            ->andWhere('grr_setting.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
