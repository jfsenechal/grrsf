<?php

namespace App\Controller;

use App\Factory\CarbonFactory;
use App\Helper\RessourceSelectedHelper;
use App\Modules\GrrModuleSenderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @var RessourceSelectedHelper
     */
    private $ressourceSelectedHelper;

    public function __construct(RessourceSelectedHelper $ressourceSelectedHelper)
    {
        $this->ressourceSelectedHelper = $ressourceSelectedHelper;
    }

    /**
     *
     * @Route("/", name="grr_home", methods={"GET"})
     */
    public function index(): Response
    {
        $today = CarbonFactory::getToday();

        $area = $this->ressourceSelectedHelper->getArea();
        $room = $this->ressourceSelectedHelper->getRoom();

        $params = ['area' => $area->getId(), 'year' => $today->year, 'month' => $today->month];

        if ($room) {
            $params['room'] = $room->getId();
        }

        return $this->redirectToRoute(
            'grr_front_month',
            $params
        );
    }

    /**
     * @Route("/modules", name="grr_modules", methods={"GET"})
     */
    public function modules(GrrModuleSenderInterface $grrModule): Response
    {
        $grrModule->postContent();

        return $this->render(
            'default/index.html.twig',
            [
            ]
        );
    }
}
