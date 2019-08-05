<?php

namespace App\Controller;

use App\Factory\MenuGenerator;
use App\Model\Month;
use App\Service\CalendarNavigationDisplay;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NavigationController
 * @package App\Controller
 * @Route("/navigation")
 */
class NavigationController extends AbstractController
{
    /**
     * @var MenuGenerator
     */
    private $menuGenerator;
    /**
     * @var CalendarNavigationDisplay
     */
    private $calendarNavigationDisplay;

    public function __construct(
        MenuGenerator $menuGenerator,
        CalendarNavigationDisplay $calendarNavigationDisplay
    ) {
        $this->menuGenerator = $menuGenerator;
        $this->calendarNavigationDisplay = $calendarNavigationDisplay;
    }

    /**
     * @Route("/month", methods={"GET"})
     */
    public function month(RequestStack $requestStack): Response
    {
        $request = $requestStack->getMasterRequest();

        if (!$request) {
            return new Response('');
        }

        $year = $request->get('year') ?? 0;
        $month = $request->get('month') ?? 0;

        $monthModel = Month::createJf($year, $month);

        $navigation = $this->calendarNavigationDisplay->createMonth($monthModel);

        return $this->render(
            'calendar/navigation/_calendar_navigation.html.twig',
            [
                'navigation' => $navigation,
                'monthModel' => $monthModel,
            ]
        );
    }

    /**
     * @Route("/menu", methods={"GET"})
     */
    public function menu(RequestStack $requestStack): Response
    {
        $request = $requestStack->getMasterRequest();
        $area = $request->get('area') ?? 0;

        $form = $this->menuGenerator->generateMenuSelect($area);

        return $this->render(
            'calendar/navigation/_area_form.html.twig',
            [
                'form' => $form->createView(),

            ]
        );
    }


}
