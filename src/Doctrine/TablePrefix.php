<?php


namespace App\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

/**
 * Pour l'activer dans config/services.yaml :
 * App\Doctrine\TablePrefix:
 *  arguments:
 *    $prefix: '%env(string:DATABASE_PREFIX)%'
 *  tags:
 *    - { name: doctrine.event_subscriber, connection: default }
 *
 * Class TablePrefix
 * @package App\Doctrine
 */
class TablePrefix implements EventSubscriber
{
    protected $prefix = 'grr_';

    public function __construct($prefix)
    {
        $this->prefix = (string)$prefix;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public function getSubscribedEvents()
    {
        return ['loadClassMetadata'];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();

        if (!$classMetadata->isInheritanceTypeSingleTable() || $classMetadata->getName(
            ) === $classMetadata->rootEntityName) {
            $classMetadata->setPrimaryTable(
                [
                    'name' => $this->prefix.$classMetadata->getTableName(),
                ]
            );
        }

        foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
            if ($mapping['type'] == \Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_MANY && $mapping['isOwningSide']) {
                $mappedTableName = $mapping['joinTable']['name'];
                $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix.$mappedTableName;
            }
        }
    }


}