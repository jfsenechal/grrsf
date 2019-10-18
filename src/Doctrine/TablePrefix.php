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
 *    - { name: doctrine.event_subscriber, connection: default }.
 *
 * Class TablePrefix
 */
class TablePrefix implements EventSubscriber
{
    /**
     * @var string
     */
    protected $prefix;

    public function __construct(string $prefix = 'grr_')
    {
        $this->prefix = $prefix;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return ['loadClassMetadata'];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
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
            if (\Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_MANY == $mapping['type'] && $mapping['isOwningSide']) {
                $mappedTableName = $mapping['joinTable']['name'];
                $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix.$mappedTableName;
            }
        }
    }
}
