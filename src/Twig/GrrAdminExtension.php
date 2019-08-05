<?php

namespace App\Twig;

use App\GrrData\DateUtils;
use App\GrrData\EntryData;
use App\GrrData\GrrConstants;
use App\Repository\TypeAreaRepository;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class GrrAdminExtension extends AbstractExtension
{
    /**
     * @var EntryData
     */
    private $entryData;
    /**
     * @var TypeAreaRepository
     */
    private $TypeAreaRepository;
    /**
     * @var DateUtils
     */
    private $dateUtils;
    /**
     * @var Environment
     */
    private $twigEnvironment;

    public function __construct(
        DateUtils $dateUtils,
        TypeAreaRepository $TypeAreaRepository,
        EntryData $entryData,
        Environment $twigEnvironment
    ) {
        $this->dateUtils = $dateUtils;
        $this->entryData = $entryData;
        $this->TypeAreaRepository = $TypeAreaRepository;
        $this->twigEnvironment = $twigEnvironment;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('periodiciteName', [$this, 'periodiciteName']),
            new TwigFilter('entryTypeGetName', [$this, 'entryTypeGetName']),
            new TwigFilter('getNumWeeks', [$this, 'getNumWeeks']),
            new TwigFilter('joursSemaine', [$this, 'joursSemaine']),
            new TwigFilter('periodName', [$this, 'periodName']),
            new TwigFilter('hourFormat', [$this, 'hourFormat']),
            new TwigFilter('displayColor', [$this, 'displayColor'], ['is_safe' => ['html']]),
        ];
    }

    public function getFunctions()
    {
        return [

        ];
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
        $room = $this->TypeAreaRepository->findOneBy(['typeLetter' => $value]);
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
        $jours = $this->dateUtils::getDays();

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

    public function displayColor(string $value)
    {
        return '<span style="background-color: '.$value.';"></span>';
    }

    public function displayLine(int $value)
    {
        if ($value !== 1) {

        }
    }


}
