<?php

namespace App\Controller;

use App\Modules\GrrModuleSenderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="grr_home", methods={"GET"})
     */
    public function index(GrrModuleSenderInterface $grrModule): Response
    {
        $grrModule->postContent();

        return $this->render(
            'default/index.html.twig',
            [
            ]
        );
    }
}
