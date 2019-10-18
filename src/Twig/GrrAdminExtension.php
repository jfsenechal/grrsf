<?php

namespace App\Twig;

use App\Provider\DateProvider;
use App\Repository\EntryTypeRepository;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class GrrAdminExtension extends AbstractExtension
{
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
        Environment $twigEnvironment
    ) {
        $this->TypeAreaRepository = $TypeAreaRepository;
        $this->twigEnvironment = $twigEnvironment;
    }

    /**
     * @return \Twig\TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter(
                'grrJoursSemaine', function ($value): string {
                    return $this->joursSemaine($value);
                }
            ),
            new TwigFilter(
                'grrDisplayColor', function (string $value): string {
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
    public function joursSemaine($value): string
    {
        $jours = DateProvider::getNamesDaysOfWeek();

        return isset($jours[$value]) ? $jours[$value] : $value;
    }

    public function displayColor(string $value): string
    {
        return '<span style="background-color: '.$value.';"></span>';
    }
}
