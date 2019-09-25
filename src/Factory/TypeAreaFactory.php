<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42.
 */

namespace App\Factory;

use App\Entity\Area;
use App\Entity\EntryType;
use App\Entity\TypeArea;
use App\Model\TypeAssocArea;
use App\Repository\TypeAreaRepository;

class TypeAreaFactory
{
    /**
     * @var TypeAreaRepository
     */
    private $typeAreaRepository;

    public function __construct(TypeAreaRepository $typeAreaRepository)
    {
        $this->typeAreaRepository = $typeAreaRepository;
    }

    public function createNew(Area $area, EntryType $entryType): TypeArea
    {
        return new TypeArea($area, $entryType);
    }

    public function createAreaAssoc(Area $area): TypeAssocArea
    {
        $areaTypes = $this->typeAreaRepository->findBy(['area' => $area]);
        $assoc = new TypeAssocArea($area);

        foreach ($areaTypes as $areaType) {
            $assoc->addType($areaType->getEntryType());
        }

        return $assoc;
    }

}
