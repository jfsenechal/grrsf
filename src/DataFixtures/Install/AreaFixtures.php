<?php

namespace App\DataFixtures\Install;

use App\Entity\Area;
use App\Factory\AreaFactory;
use App\Factory\RoomFactory;
use App\Repository\AreaRepository;
use App\Repository\RoomRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AreaFixtures extends Fixture
{
    /**
     * @var AreaFactory
     */
    private $areaFactory;
    /**
     * @var RoomFactory
     */
    private $roomFactory;
    /**
     * @var AreaRepository
     */
    private $areaRepository;
    /**
     * @var RoomRepository
     */
    private $roomRepository;
    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(
        AreaRepository $areaRepository,
        AreaFactory $areaFactory,
        RoomFactory $roomFactory,
        RoomRepository $roomRepository
    ) {
        $this->areaFactory = $areaFactory;
        $this->roomFactory = $roomFactory;
        $this->areaRepository = $areaRepository;
        $this->roomRepository = $roomRepository;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $esquareName = 'E-square';
        $esquare = $this->areaRepository->findOneBy(['name' => $esquareName]);
        if (!$esquare) {
            $esquare = $this->areaFactory->createNew();
            $esquare->setName($esquareName);
            $manager->persist($esquare);
        }

        $hdvName = 'Hdv';
        $hdv = $this->areaRepository->findOneBy(['name' => $hdvName]);
        if (!$hdv) {
            $hdv = $this->areaFactory->createNew();
            $hdv->setName($hdvName);
            $manager->persist($hdv);
        }

        $salles = [
            'Box',
            'Créative',
            'Meeting Room',
            'Relax Room',
            'Digital Room',
        ];

        $this->loadRooms($esquare, $salles);

        $salles = [
            'Salle Conseil',
            'Salle Collège',
            'Salle cafétaria',
        ];

        $this->loadRooms($esquare, $salles);

        $manager->flush();
    }

    public function loadRooms(Area $area, array $salles)
    {
        foreach ($salles as $salle) {
            if ($this->roomRepository->findOneBy(['name' => $salle])) {
                continue;
            }
            $room = $this->roomFactory->createNew($area);
            $room->setName($salle);
            $this->manager->persist($room);
            $this->addReference($salle, $room);
        }
    }
}
