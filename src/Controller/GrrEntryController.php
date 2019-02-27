<?php

namespace App\Controller;

use App\Entity\GrrEntry;
use App\Form\GrrEntryType;
use App\Repository\GrrEntryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/grr/entry")
 */
class GrrEntryController extends AbstractController
{
    /**
     * @Route("/", name="grr_entry_index", methods={"GET"})
     */
    public function index(GrrEntryRepository $grrEntryRepository): Response
    {
        return $this->render('grr_entry/index.html.twig', [
            'grr_entries' => $grrEntryRepository->search(),
        ]);
    }

    /**
     * @Route("/new", name="grr_entry_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $grrEntry = new GrrEntry();
        $form = $this->createForm(GrrEntryType::class, $grrEntry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($grrEntry);
            $entityManager->flush();

            return $this->redirectToRoute('grr_entry_index');
        }

        return $this->render('grr_entry/new.html.twig', [
            'grr_entry' => $grrEntry,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="grr_entry_show", methods={"GET"})
     */
    public function show(GrrEntry $grrEntry): Response
    {
        return $this->render('grr_entry/show.html.twig', [
            'grr_entry' => $grrEntry,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="grr_entry_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, GrrEntry $grrEntry): Response
    {
        $form = $this->createForm(GrrEntryType::class, $grrEntry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('grr_entry_index', [
                'id' => $grrEntry->getId(),
            ]);
        }

        return $this->render('grr_entry/edit.html.twig', [
            'grr_entry' => $grrEntry,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="grr_entry_delete", methods={"DELETE"})
     */
    public function delete(Request $request, GrrEntry $grrEntry): Response
    {
        if ($this->isCsrfTokenValid('delete'.$grrEntry->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($grrEntry);
            $entityManager->flush();
        }

        return $this->redirectToRoute('grr_entry_index');
    }
}
