<?php

namespace App\Twig;

use App\Entity\Entry;
use App\Factory\MenuGenerator;
use App\Model\Day;
use App\Model\Month;
use App\Model\RoomModel;
use App\Model\TimeSlot;
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

    public function getFunctions()
    {
        return [
            new TwigFunction('grrMonthNavigationRender', function () {
                return $this->monthNavigationRender();
            }, ['is_safe' => ['html']]),
            new TwigFunction('grrMenuNavigationRender', function () {
                return $this->menuNavigationRender();
            }, ['is_safe' => ['html']]),
            new TwigFunction('grrCompleteTr', function (CarbonInterface $day, string $action) {
                return $this->grrCompleteTr($day, $action);
            }, ['is_safe' => ['html']]),
            new TwigFunction('grrGenerateCellDataDay', function (TimeSlot $hour, RoomModel $roomModel, Day $day) {
                return $this->grrGenerateCellDataDay($hour, $roomModel, $day);
            }, ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param TimeSlot  $hour
     * @param RoomModel $roomModel
     *
     * @return string|void
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function grrGenerateCellDataDay(TimeSlot $hour, RoomModel $roomModel, Day $day)
    {
        /**
         * @var Entry[]
         */
        $entries = $roomModel->getEntries();
        foreach ($entries as $entry) {
            /**
             * @var TimeSlot[]
             */
            $locations = $entry->getLocations();
            $position = 0;
            foreach ($locations as $location) {
                if ($location === $hour) {
                    if (0 === $position) {
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
     * @return string
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function monthNavigationRender()
    {
        $request = $this->requestStack->getMasterRequest();

        if ($request === null) {
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

    /**
     * @return string
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function menuNavigationRender()
    {
        $request = $this->requestStack->getMasterRequest();
        $area = $request !== null ? $request->get('area') : 0;

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
