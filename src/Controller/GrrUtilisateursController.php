<?php

namespace App\Controller;

use App\Entity\GrrUtilisateurs;
use App\Form\GrrUtilisateursType;
use App\Repository\GrrUtilisateursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/grr/utilisateurs")
 */
class GrrUtilisateursController extends AbstractController
{
    /**
     * @Route("/", name="grr_utilisateurs_index", methods={"GET"})
     */
    public function index(GrrUtilisateursRepository $grrUtilisateursRepository): Response
    {
        return $this->render('grr_utilisateurs/index.html.twig', [
            'grr_utilisateurs' => $grrUtilisateursRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="grr_utilisateurs_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $grrUtilisateur = new GrrUtilisateurs();
        $form = $this->createForm(GrrUtilisateursType::class, $grrUtilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($grrUtilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('grr_utilisateurs_index');
        }

        return $this->render('grr_utilisateurs/new.html.twig', [
            'grr_utilisateur' => $grrUtilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{login}", name="grr_utilisateurs_show", methods={"GET"})
     */
    public function show(GrrUtilisateurs $grrUtilisateur): Response
    {
        return $this->render('grr_utilisateurs/show.html.twig', [
            'grr_utilisateur' => $grrUtilisateur,
        ]);
    }

    /**
     * @Route("/{login}/edit", name="grr_utilisateurs_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, GrrUtilisateurs $grrUtilisateur): Response
    {
        $form = $this->createForm(GrrUtilisateursType::class, $grrUtilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('grr_utilisateurs_index', [
                'login' => $grrUtilisateur->getLogin(),
            ]);
        }

        return $this->render('grr_utilisateurs/edit.html.twig', [
            'grr_utilisateur' => $grrUtilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{login}", name="grr_utilisateurs_delete", methods={"DELETE"})
     */
    public function delete(Request $request, GrrUtilisateurs $grrUtilisateur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$grrUtilisateur->getLogin(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($grrUtilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('grr_utilisateurs_index');
    }
}
