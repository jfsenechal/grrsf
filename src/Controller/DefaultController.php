<?php

namespace App\Controller;

use App\Message\SmsNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @var MessageBusInterface
     */
    private $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @Route("/", name="grr_home", methods={"GET"})
     */
    public function index(): Response
    {
        $this->bus->dispatch(new SmsNotification('A string to be sent...'));

        return $this->render(
            'default/index.html.twig',
            [

            ]
        );
    }

}
