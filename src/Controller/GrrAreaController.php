<?php

namespace App\Controller;

use App\Entity\GrrArea;
use App\Factory\AreaFactory;
use App\Form\GrrAreaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/grr/area")
 */
class GrrAreaController extends AbstractController
{
    /**
     * @var AreaFactory
     */
    private $areaFactory;

    public function __construct(AreaFactory $areaFactory)
    {
        $this->areaFactory = $areaFactory;
    }

    /**
     * @Route("/", name="grr_area_index", methods={"GET"})
     */
    public function index(): Response
    {
        $grrAreas = $this->getDoctrine()
            ->getRepository(GrrArea::class)
            ->findAll();

        return $this->render('grr_area/index.html.twig', [
            'grr_areas' => $grrAreas,
        ]);
    }

    /**
     * @Route("/new", name="grr_area_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $grrArea = $this->areaFactory->createNew();
        $this->areaFactory->setDefaultValues($grrArea);

        $form = $this->createForm(GrrAreaType::class, $grrArea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($grrArea);
            $entityManager->flush();

            return $this->redirectToRoute('grr_area_index');
        }

        return $this->render('grr_area/new.html.twig', [
            'grr_area' => $grrArea,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="grr_area_show", methods={"GET"})
     */
    public function show(GrrArea $grrArea): Response
    {
        return $this->render('grr_area/show.html.twig', [
            'grr_area' => $grrArea,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="grr_area_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, GrrArea $grrArea): Response
    {
        $form = $this->createForm(GrrAreaType::class, $grrArea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('grr_area_index', [
                'id' => $grrArea->getId(),
            ]);
        }

        return $this->render('grr_area/edit.html.twig', [
            'grr_area' => $grrArea,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="grr_area_delete", methods={"DELETE"})
     */
    public function delete(Request $request, GrrArea $grrArea): Response
    {
        if ($this->isCsrfTokenValid('delete'.$grrArea->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($grrArea);
            $entityManager->flush();
        }

        return $this->redirectToRoute('grr_area_index');
    }
}
