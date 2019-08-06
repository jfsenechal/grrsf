<?php

namespace App\Twig;

use App\Factory\MenuGenerator;
use App\Model\Day;
use App\Model\Month;
use App\Service\CalendarNavigationDisplay;
use Carbon\CarbonInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
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
    /**
     * @var MenuGenerator
     */
    private $menuGenerator;

    public function __construct(
        RequestStack $requestStack,
        MenuGenerator $menuGenerator,
        Environment $twigEnvironment,
        RouterInterface $router,
        CalendarNavigationDisplay $calendarNavigationDisplay
    ) {
        $this->twigEnvironment = $twigEnvironment;
        $this->calendarNavigationDisplay = $calendarNavigationDisplay;
        $this->requestStack = $requestStack;
        $this->menuGenerator = $menuGenerator;
        $this->router = $router;
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
            new TwigFunction('completeTr', [$this, 'completeTr'], ['is_safe' => ['html']]),
            new TwigFunction('generateRouteMonthView', [$this, 'generateRouteMonthView']),
            new TwigFunction('generateRouteWeekView', [$this, 'generateRouteWeekView']),
            new TwigFunction('generateRouteDayView', [$this, 'generateRouteDayView']),
            new TwigFunction('generateRouteAddEntry', [$this, 'generateRouteAddEntry']),
        ];
    }

    /**
     * @param Month $monthModel
     * @return string
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

        $monthModel = Month::createJf($year, $month);

        $navigation = $this->calendarNavigationDisplay->createMonth($monthModel);

        return $this->twigEnvironment->render(
            'calendar/navigation/month/_calendar_navigation.html.twig',
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
            'calendar/navigation/form/_area_form.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @param Day $day
     * @param string $action begin|end
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function completeTr(CarbonInterface $day, string $action)
    {
        return $this->twigEnvironment->render(
            'calendar/_complete_tr.html.twig',
            ['numericDay' => $day->weekday(), 'action' => $action]
        );
    }

    public function generateRouteMonthView(int $year = null, int $month = null)
    {
        $request = $this->requestStack->getMasterRequest();
        if (!$request) {
            return '';
        }

        $attributes = $request->attributes->get('_route_params');

        $area = $attributes['area'] ?? 0;
        $room = $attributes['room'] ?? 0;

        if (!$year) {
            $year = (int)$attributes['year'];
        }
        if (!$month) {
            $month = (int)$attributes['month'];
        }

        $params = ['area' => $area, 'year' => $year, 'month' => $month];

        if ($room) {
            $params['room'] = $room;
        }

        return $this->router->generate('grr_front_month', $params);
    }

    public function generateRouteWeekView(int $week)
    {
        $request = $this->requestStack->getMasterRequest();
        if (!$request) {
            return '';
        }

        $attributes = $request->attributes->get('_route_params');

        $area = $attributes['area'] ?? 0;
        $room = $attributes['room'] ?? 0;
        $year = $attributes['year'] ?? 0;
        $month = $attributes['month'] ?? 0;

        $params = ['area' => $area, 'year' => $year, 'month' => $month, 'week' => $week];

        if ($room) {
            $params['room'] = (int)$room;
        }

        return $this->router->generate('grr_front_week', $params);
    }

    public function generateRouteDayView(int $day)
    {
        $request = $this->requestStack->getMasterRequest();
        if (!$request) {
            return '';
        }

        $attributes = $request->attributes->get('_route_params');

        $area = $attributes['area'] ?? 0;
        $room = $attributes['room'] ?? 0;
        $year = $attributes['year'] ?? 0;
        $month = $attributes['month'] ?? 0;

        $params = ['area' => $area, 'year' => $year, 'month' => $month, 'day' => $day];

        if ($room) {
            $params['room'] = $room;
        }

        return $this->router->generate('grr_front_day', $params);
    }

    public function generateRouteAddEntry(int $area, int $room, int $day)
    {
        $request = $this->requestStack->getMasterRequest();
        if (!$request) {
            return '';
        }

        $attributes = $request->attributes->get('_route_params');

        $year = $attributes['year'] ?? 0;
        $month = $attributes['month'] ?? 0;

        $params = ['area' => $area, 'room' => $room, 'year' => $year, 'month' => $month, 'day' => $day];

        return $this->router->generate('grr_entry_new', $params);
    }
}
