<?php

namespace App\Twig;

use App\Factory\MenuGenerator;
use App\Model\Day;
use App\Model\Hour;
use App\Model\Month;
use App\Model\RoomModel;
use App\Navigation\NavigationManager;
use Carbon\CarbonInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GrrFrontExtension extends AbstractExtension
{
    /**
     * @var Environment
     */
    private $twigEnvironment;
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var NavigationManager
     */
    private $calendarNavigationDisplay;
    /**
     * @var MenuGenerator
     */
    private $menuGenerator;

    public function __construct(
        RequestStack $requestStack,
        MenuGenerator $menuGenerator,
        Environment $twigEnvironment,
        NavigationManager $calendarNavigationDisplay
    ) {
        $this->twigEnvironment = $twigEnvironment;
        $this->calendarNavigationDisplay = $calendarNavigationDisplay;
        $this->requestStack = $requestStack;
        $this->menuGenerator = $menuGenerator;
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
            new TwigFunction('grrMonthNavigation', [$this, 'MonthNavigation'], ['is_safe' => ['html']]),
            new TwigFunction('grrMenuNavigation', [$this, 'menuNavigation'], ['is_safe' => ['html']]),
            new TwigFunction('grrCompleteTr', [$this, 'grrCompleteTr'], ['is_safe' => ['html']]),
            new TwigFunction('grrGenerateCellDataDay', [$this, 'grrGenerateCellDataDay'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param Hour      $hour
     * @param RoomModel $roomModel
     *
     * @return string|void
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function grrGenerateCellDataDay(Hour $hour, RoomModel $roomModel, Day $day)
    {
        $entries = $roomModel->getEntries();
        foreach ($entries as $entry) {
            /**
             * @var Hour[]
             */
            $locations = $entry->getLocations();
            $position = 0;
            foreach ($locations as $location) {
                if ($location->getBegin()->equalTo(
                    $hour->getBegin() && $location->getEnd()->equalTo($hour->getEnd())
                )) {
                    if (0 == $position) {
                        return $this->twigEnvironment->render(
                            '@grr_front/day/_cell_day_data.html.twig',
                            ['position' => $position, 'entry' => $entry]
                        );
                    }

                    return;
                }
                ++$position;
            }
        }

        $room = $roomModel->getRoom();
        $area = $room->getArea();

        return $this->twigEnvironment->render(
            '@grr_front/day/_cell_day_empty.html.twig',
            ['position' => 999, 'area' => $area, 'room' => $room, 'day' => $day, 'hourModel' => $hour]
        );
    }

    /**
     * @param Month $monthModel
     *
     * @return string
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function monthNavigation()
    {
        $request = $this->requestStack->getMasterRequest();

        if (!$request) {
            return new Response('');
        }

        $year = $request->get('year') ?? 0;
        $month = $request->get('month') ?? 0;

        $monthModel = Month::init($year, $month);

        $navigation = $this->calendarNavigationDisplay->createMonth($monthModel);

        return $this->twigEnvironment->render(
            '@grr_front/navigation/month/_calendar_navigation.html.twig',
            [
                'navigation' => $navigation,
                'monthModel' => $monthModel,
            ]
        );
    }

    public function menuNavigation()
    {
        $request = $this->requestStack->getMasterRequest();
        $area = $request ? $request->get('area') : 0;

        $form = $this->menuGenerator->generateMenuSelect($area);

        return $this->twigEnvironment->render(
            '@grr_front/navigation/form/_area_form.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @param Day    $day
     * @param string $action begin|end
     *
     * @return string
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function grrCompleteTr(CarbonInterface $day, string $action)
    {
        return $this->twigEnvironment->render(
            '@grr_front/navigation/_complete_tr.html.twig',
            ['numericDay' => $day->weekday(), 'action' => $action]
        );
    }
}
