<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 * @IsGranted("ROLE_GRR")
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
