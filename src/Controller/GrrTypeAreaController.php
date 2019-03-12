<?php

namespace App\Controller;

use App\Entity\GrrTypeArea;
use App\Factory\TypeAreaFactory;
use App\Form\GrrTypeAreaType;
use App\Manager\GrrTypeAreaManager;
use App\Repository\GrrTypeAreaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/grr/type/area")
 */
class GrrTypeAreaController extends AbstractController
{
    /**
     * @var TypeAreaFactory
     */
    private $typeAreaFactory;
    /**
     * @var GrrTypeAreaRepository
     */
    private $grrTypeAreaRepository;
    /**
     * @var GrrTypeAreaManager
     */
    private $typeAreaManager;

    public function __construct(
        TypeAreaFactory $typeAreaFactory,
        GrrTypeAreaRepository $grrTypeAreaRepository,
        GrrTypeAreaManager $typeAreaManager
    ) {
        $this->typeAreaFactory = $typeAreaFactory;
        $this->grrTypeAreaRepository = $grrTypeAreaRepository;
        $this->typeAreaManager = $typeAreaManager;
    }

    /**
     * @Route("/", name="grr_type_area_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render(
            'grr_type_area/index.html.twig',
            [
                'grr_type_areas' => $this->grrTypeAreaRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="grr_type_area_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $grrTypeArea = $this->typeAreaFactory->createNew();
        $this->typeAreaFactory->setDefaultValues($grrTypeArea);

        $form = $this->createForm(GrrTypeAreaType::class, $grrTypeArea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->grrTypeAreaRepository->insert($grrTypeArea);

            return $this->redirectToRoute('grr_type_area_index');
        }

        return $this->render(
            'grr_type_area/new.html.twig',
            [
                'grr_type_area' => $grrTypeArea,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_type_area_show", methods={"GET"})
     */
    public function show(GrrTypeArea $grrTypeArea): Response
    {
        return $this->render(
            'grr_type_area/show.html.twig',
            [
                'grr_type_area' => $grrTypeArea,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="grr_type_area_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, GrrTypeArea $grrTypeArea): Response
    {
        $form = $this->createForm(GrrTypeAreaType::class, $grrTypeArea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->grrTypeAreaRepository->flush();

            return $this->redirectToRoute(
                'grr_type_area_index',
                [
                    'id' => $grrTypeArea->getId(),
                ]
            );
        }

        return $this->render(
            'grr_type_area/edit.html.twig',
            [
                'grr_type_area' => $grrTypeArea,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_type_area_delete", methods={"DELETE"})
     */
    public function delete(Request $request, GrrTypeArea $grrTypeArea): Response
    {
        if ($this->isCsrfTokenValid('delete'.$grrTypeArea->getId(), $request->request->get('_token'))) {
            $this->grrTypeAreaRepository->persist($grrTypeArea);
            $this->grrTypeAreaRepository->flush();
        }

        return $this->redirectToRoute('grr_type_area_index');
    }
}
