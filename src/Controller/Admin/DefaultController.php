<?php

namespace App\Controller\Admin;

use App\Entity\Area;
use App\Factory\AreaFactory;
use App\Form\AreaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="grr_admin_index", methods={"GET"})
     */
    public function index(): Response
    {

        return $this->render(
            '@grr_admin/default/index.html.twig',
            [

            ]
        );
    }


}
