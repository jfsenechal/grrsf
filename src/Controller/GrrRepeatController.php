<?php

namespace App\Controller;

use App\Entity\GrrRepeat;
use App\Form\GrrRepeatType;
use App\Repository\GrrRepeatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/grr/repeat")
 */
class GrrRepeatController extends AbstractController
{
    /**
     * @Route("/", name="grr_repeat_index", methods={"GET"})
     */
    public function index(GrrRepeatRepository $grrRepeatRepository): Response
    {
        return $this->render('grr_repeat/index.html.twig', [
            'grr_repeats' => $grrRepeatRepository->search(),
        ]);
    }

    /**
     * @Route("/new", name="grr_repeat_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $grrRepeat = new GrrRepeat();
        $form = $this->createForm(GrrRepeatType::class, $grrRepeat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($grrRepeat);
            $entityManager->flush();

            return $this->redirectToRoute('grr_repeat_index');
        }

        return $this->render('grr_repeat/new.html.twig', [
            'grr_repeat' => $grrRepeat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="grr_repeat_show", methods={"GET"})
     */
    public function show(GrrRepeat $grrRepeat): Response
    {
        return $this->render('grr_repeat/show.html.twig', [
            'grr_repeat' => $grrRepeat,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="grr_repeat_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, GrrRepeat $grrRepeat): Response
    {
        $form = $this->createForm(GrrRepeatType::class, $grrRepeat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('grr_repeat_index', [
                'id' => $grrRepeat->getId(),
            ]);
        }

        return $this->render('grr_repeat/edit.html.twig', [
            'grr_repeat' => $grrRepeat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="grr_repeat_delete", methods={"DELETE"})
     */
    public function delete(Request $request, GrrRepeat $grrRepeat): Response
    {
        if ($this->isCsrfTokenValid('delete'.$grrRepeat->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($grrRepeat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('grr_repeat_index');
    }
}
