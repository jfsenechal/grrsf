<?php

namespace App\Controller;

use App\Entity\GrrArea;
use App\Form\GrrAreaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="grr_home", methods={"GET"})
     */
    public function index(): Response
    {

        return $this->render('default/index.html.twig', [

        ]);
    }


}
