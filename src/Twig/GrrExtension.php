<?php

namespace App\Twig;

use App\GrrData\EntryData;
use App\Repository\GrrRoomRepository;
use App\Repository\GrrTypeAreaRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class GrrExtension extends AbstractExtension
{
    /**
     * @var GrrRoomRepository
     */
    private $grrRoomRepository;
    /**
     * @var EntryData
     */
    private $entryData;
    /**
     * @var GrrTypeAreaRepository
     */
    private $grrTypeAreaRepository;

    public function __construct(
        GrrRoomRepository $grrRoomRepository,
        GrrTypeAreaRepository $grrTypeAreaRepository,
        EntryData $entryData
    ) {
        $this->grrRoomRepository = $grrRoomRepository;
        $this->entryData = $entryData;
        $this->grrTypeAreaRepository = $grrTypeAreaRepository;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('room_getName', [$this, 'roomGetName']),
            new TwigFilter('periodiciteName', [$this, 'periodiciteName']),
            new TwigFilter('entryTypeGetName', [$this, 'entryTypeGetName']),
            new TwigFilter('getNumWeeks', [$this, 'getNumWeeks']),
            new TwigFilter('joursSemaine', [$this, 'joursSemaine']),
        ];
    }

    public function roomGetName($value)
    {
        $room = $this->grrRoomRepository->find($value);
        if ($room) {
            return $room->getRoomName().' ('.$value.')';
        }

        return $value;
    }

    public function periodiciteName($value)
    {
        return $this->entryData->getTypePeriodicite($value);
    }

    /**
     * field: type
     * @param $value
     * @return string
     */
    public function entryTypeGetName($value)
    {
        $room = $this->grrTypeAreaRepository->findOneBy(['typeLetter' => $value]);
        if ($room) {
            return $room->getTypeName().' ('.$value.')';
        }

        return $value;
    }

    /**
     *field: rep_num_weeks
     * @param $value
     * @return mixed
     */
    public function getNumWeeks($value)
    {
        return $this->entryData->getNumWeeks($value).' ('.$value.')';
    }

    /**
     * field:repOpt
     * 7 chiffres
     * @param $value
     */
    public function joursSemaine($value)
    {
        return $value;

    }

}
