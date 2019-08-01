<?php

namespace App\Twig;

use App\GrrData\DateUtils;
use App\GrrData\EntryData;
use App\Model\Month;
use App\Repository\RoomRepository;
use App\Repository\TypeAreaRepository;
use App\Service\CalendarNavigationDisplay;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GrrNavigationExtension extends AbstractExtension
{
    /**
     * @var RoomRepository
     */
    private $roomRepository;
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
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var CalendarNavigationDisplay
     */
    private $calendarNavigationDisplay;

    public function __construct(
        Environment $twigEnvironment,
        CalendarNavigationDisplay $calendarNavigationDisplay
    ) {
        $this->twigEnvironment = $twigEnvironment;
        $this->calendarNavigationDisplay = $calendarNavigationDisplay;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping

        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('monthNavigation', [$this, 'monthNavigation'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param Month $monthModel
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function monthNavigation(Month $monthModel)
    {
        $navigation = $this->calendarNavigationDisplay->createMonth($monthModel);
        $form = '';

        return $this->twigEnvironment->render(
            'calendar/navigation/_navigation.html.twig',
            [
                'navigation' => $navigation,
                'form' => $form,
            ]
        );

    }
}
