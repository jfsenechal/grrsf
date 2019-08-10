<?php

namespace App\Controller\Front;

use App\Entity\Periodicity;
use App\Repository\PeriodicityRepository;
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
     * @Route("/{id}", name="periodicity_show", methods={"GET"})
     */
    public function show(Periodicity $periodicity): Response
    {
        return $this->render(
            'periodicity/show.html.twig',
            [
                'periodicity' => $periodicity,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="periodicity_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Periodicity $periodicity): Response
    {
        $form = $this->createForm(Periodicity1Type::class, $periodicity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('periodicity_index');
        }

        return $this->render(
            'periodicity/edit.html.twig',
            [
                'periodicity' => $periodicity,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="periodicity_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Periodicity $periodicity): Response
    {
        if ($this->isCsrfTokenValid('delete'.$periodicity->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($periodicity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('periodicity_index');
    }
}
