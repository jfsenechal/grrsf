<?php

namespace App\Twig;

use App\GrrData\EntryData;
use App\GrrData\GrrConstants;
use App\Provider\DateProvider;
use App\Repository\EntryTypeRepository;
use App\Settings\SettingsArea;
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
     * @var Environment
     */
    private $twigEnvironment;

    public function __construct(
        EntryTypeRepository $TypeAreaRepository,
        EntryData $entryData,
        Environment $twigEnvironment
    ) {
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
            new TwigFilter(
                'grrJoursSemaine', function ($value) {
                return $this->joursSemaine($value);
            }
            ),
            new TwigFilter(
                'grrPeriodName', function (int $value) {
                return $this->periodName($value);
            }
            ),
            new TwigFilter(
                'grrHourFormat', function (int $value) {
                return $this->hourFormat($value);
            }
            ),
            new TwigFilter(
                'grrDisplayColor', function (string $value) {
                return $this->displayColor($value);
            }, ['is_safe' => ['html']]
            ),
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
        $jours = DateProvider::getNamesDaysOfWeek();

        return isset($jours[$value]) ? $jours[$value] : $value;
    }

    public function periodName(int $value)
    {
        return GrrConstants::PERIOD[$value];
    }

    public function hourFormat(int $value)
    {
        return SettingsArea::getAffichageFormat()[$value];
    }

    public function displayColor(string $value)
    {
        return '<span style="background-color: '.$value.';"></span>';
    }
}
