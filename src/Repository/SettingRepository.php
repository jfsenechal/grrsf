<?php

namespace App\Repository;

use App\Entity\Setting;
use App\Setting\SettingConstants;
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

    /**
     * @param string $name
     *
     * @return Setting|null
     *
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

    public function load()
    {
        $data = [];

        foreach ($this->findAll() as $setting) {
            $value = $setting->getValue();
            if (in_array(
                $setting->getName(),
                [SettingConstants::WEBMASTER_EMAIL, SettingConstants::TECHNICAL_SUPPORT_EMAIL],
                true
            )) {
                $value = unserialize($value);
            }
            $data[$setting->getName()] = $value;
        }

        return $data;
    }
}
