<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GrrhUrlHelperExtension extends AbstractExtension
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

    public function __construct(
        RequestStack $requestStack,
        Environment $twigEnvironment,
        RouterInterface $router
    ) {
        $this->twigEnvironment = $twigEnvironment;
        $this->requestStack = $requestStack;
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction(
                'grrGenerateRouteMonthView', function (int $year = null, int $month = null) {
                    return $this->generateRouteMonthView($year, $month);
                }
            ),
            new TwigFunction(
                'grrGenerateRouteWeekView', function (int $week) {
                    return $this->generateRouteWeekView($week);
                }
            ),
            new TwigFunction(
                'grrGenerateRouteDayView', function (int $day) {
                    return $this->generateRouteDayView($day);
                }
            ),
            new TwigFunction(
                'grrGenerateRouteAddEntry',
                function (int $area, int $room, int $day, int $hour = null, int $minute = null) {
                    return $this->generateRouteAddEntry($area, $room, $day, $hour, $minute);
                }
            ),
        ];
    }

    public function generateRouteMonthView(int $year = null, int $month = null)
    {
        $request = $this->requestStack->getMasterRequest();
        if (null === $request) {
            return '';
        }

        $attributes = $request->attributes->get('_route_params');

        $area = $attributes['area'] ?? 0;
        $room = $attributes['room'] ?? 0;

        if (!$year) {
            $year = (int) $attributes['year'];
        }
        if (!$month) {
            $month = (int) $attributes['month'];
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
        if (null === $request) {
            return '';
        }

        $attributes = $request->attributes->get('_route_params');

        $area = $attributes['area'] ?? 0;
        $room = $attributes['room'] ?? 0;
        $year = $attributes['year'] ?? 0;
        $month = $attributes['month'] ?? 0;

        $params = ['area' => $area, 'year' => $year, 'month' => $month, 'week' => $week];

        if ($room) {
            $params['room'] = (int) $room;
        }

        return $this->router->generate('grr_front_week', $params);
    }

    public function generateRouteDayView(int $day)
    {
        $request = $this->requestStack->getMasterRequest();
        if (null === $request) {
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

    public function generateRouteAddEntry(int $area, int $room, int $day, int $hour = null, int $minute = null)
    {
        $request = $this->requestStack->getMasterRequest();
        if (null === $request) {
            return '';
        }

        $attributes = $request->attributes->get('_route_params');

        $year = $attributes['year'] ?? 0;
        $month = $attributes['month'] ?? 0;
        $hour = $hour ?? 0;
        $minute = $minute ?? 0;

        $params = [
            'area' => $area,
            'room' => $room,
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'hour' => $hour,
            'minute' => $minute,
        ];

        return $this->router->generate('grr_front_entry_new', $params);
    }
}
