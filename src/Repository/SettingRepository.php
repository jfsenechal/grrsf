<?php

namespace App\Repository;

use App\Entity\Setting;
use App\Setting\SettingConstants;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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
     * @return string|null
     *
     */
    public function getValueByName(string $name): ?string
    {
        try {
            $setting = $this->createQueryBuilder('setting')
                ->andWhere('setting.name = :name')
                ->setParameter('name', $name)
                ->getQuery()
                ->getOneOrNullResult();
            if ($setting) {
                return $setting->getValue();
            }

        } catch (NonUniqueResultException $e) {

        }

        return null;
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

        /**
         * Pour bug js form edit
         */
        if (!isset($data[SettingConstants::WEBMASTER_EMAIL]) || count($data[SettingConstants::WEBMASTER_EMAIL]) === 0) {
            $data[SettingConstants::WEBMASTER_EMAIL] = [''];
        }

        if (!isset($data[SettingConstants::TECHNICAL_SUPPORT_EMAIL]) || count(
                $data[SettingConstants::TECHNICAL_SUPPORT_EMAIL]
            ) === 0) {
            $data[SettingConstants::TECHNICAL_SUPPORT_EMAIL] = [''];
        }

        return $data;
    }
}
