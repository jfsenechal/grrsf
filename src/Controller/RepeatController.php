<?php

namespace App\Controller;

use App\Entity\Repeat;
use App\Form\RepeatType;
use App\Repository\RepeatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/grr/repeat")
 */
class RepeatController extends AbstractController
{
    /**
     * @Route("/", name="grr_repeat_index", methods={"GET"})
     */
    public function index(RepeatRepository $repeatRepository): Response
    {
        return $this->render('repeat/index.html.twig', [
            'repeats' => $repeatRepository->search(),
        ]);
    }

    /**
     * @Route("/new", name="grr_repeat_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $repeat = new Repeat();
        $form = $this->createForm(RepeatType::class, $repeat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($repeat);
            $entityManager->flush();

            return $this->redirectToRoute('grr_repeat_index');
        }

        return $this->render('repeat/new.html.twig', [
            'repeat' => $repeat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="grr_repeat_show", methods={"GET"})
     */
    public function show(Repeat $repeat): Response
    {
        return $this->render('repeat/show.html.twig', [
            'repeat' => $repeat,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="grr_repeat_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Repeat $repeat): Response
    {
        $form = $this->createForm(RepeatType::class, $repeat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('grr_repeat_index', [
                'id' => $repeat->getId(),
            ]);
        }

        return $this->render('repeat/edit.html.twig', [
            'repeat' => $repeat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="grr_repeat_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Repeat $repeat): Response
    {
        if ($this->isCsrfTokenValid('delete'.$repeat->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($repeat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('grr_repeat_index');
    }
}
