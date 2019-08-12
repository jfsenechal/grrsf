<?php

namespace App\Controller\Front;

use App\Entity\Periodicity;
use App\Manager\PeriodicityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/periodicity")
 */
class PeriodicityController extends AbstractController
{
    /**
     * @var PeriodicityManager
     */
    private $periodicityManager;

    public function __construct(PeriodicityManager $periodicityManager)
    {
        $this->periodicityManager = $periodicityManager;
    }

    /**
     * @Route("/{id}", name="periodicity_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Periodicity $periodicity): Response
    {
        $entry = $periodicity->getEntry();

        if ($this->isCsrfTokenValid('delete'.$periodicity->getId(), $request->request->get('_token'))) {
            $this->periodicityManager->remove($periodicity);
        }

        return $this->redirectToRoute('grr_front_entry_show', ['id' => $entry->getId()]);
    }
}
