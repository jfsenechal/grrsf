<?php

namespace App\Twig;

use App\GrrData\DateUtils;
use App\GrrData\EntryData;
use App\GrrData\GrrConstants;
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
    /**
     * @var DateUtils
     */
    private $dateUtils;
    /**
     * @var GrrConstants
     */
    private $grrConstants;

    public function __construct(
        DateUtils $dateUtils,
        GrrRoomRepository $grrRoomRepository,
        GrrTypeAreaRepository $grrTypeAreaRepository,
        EntryData $entryData

    ) {
        $this->dateUtils = $dateUtils;
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
            new TwigFilter('periodName', [$this, 'periodName']),
            new TwigFilter('hourFormat', [$this, 'hourFormat']),
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
     * @return string
     */
    public function joursSemaine($value)
    {
        $jours = $this->dateUtils->getJoursSemaine();

        return isset($jours[$value]) ? $jours[$value] : $value;
    }

    public function periodName(int $value)
    {
        return GrrConstants::PERIOD[$value];
    }

    public function hourFormat(int $value)
    {
        return $this->dateUtils->getAffichageFormat()[$value];
    }
}
