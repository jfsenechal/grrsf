<?php

namespace App\Controller;

use App\Factory\CarbonFactory;
use App\Navigation\RessourceSelectedHelper;
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
    /**
     * @var CarbonFactory
     */
    private $carbonFactory;

    public function __construct(CarbonFactory $carbonFactory, RessourceSelectedHelper $ressourceSelectedHelper)
    {
        $this->ressourceSelectedHelper = $ressourceSelectedHelper;
        $this->carbonFactory = $carbonFactory;
    }

    /**
     * @Route("/", name="grr_home", methods={"GET"})
     */
    public function index(): Response
    {
        $today = $this->carbonFactory->getToday();

        try {
            $area = $this->ressourceSelectedHelper->getArea();
        } catch (\Exception $e) {
            return new Response($e->getMessage());
        }

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
