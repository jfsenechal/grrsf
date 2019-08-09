<?php

namespace App\Twig;

use App\GrrData\AreaData;
use App\GrrData\DateUtils;
use App\GrrData\EntryData;
use App\GrrData\GrrConstants;
use App\Repository\EntryTypeRepository;
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
     * @var EntryTypeRepository
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
        EntryTypeRepository $TypeAreaRepository,
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
            new TwigFilter('grrJoursSemaine', [$this, 'joursSemaine']),
            new TwigFilter('grrPeriodName', [$this, 'periodName']),
            new TwigFilter('grrHourFormat', [$this, 'hourFormat']),
            new TwigFilter('grrDisplayColor', [$this, 'displayColor'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * field:repOpt
     * 7 chiffres.
     *
     * @param $value
     *
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
        return AreaData::getAffichageFormat()[$value];
    }

    public function displayColor(string $value)
    {
        return '<span style="background-color: '.$value.';"></span>';
    }
}
