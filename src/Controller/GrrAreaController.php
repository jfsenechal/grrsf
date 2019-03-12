<?php

namespace App\Controller;

use App\Entity\GrrArea;
use App\Factory\GrrAreaFactory;
use App\Form\GrrAreaType;
use App\Manager\GrrAreaManager;
use App\Repository\GrrAreaRepository;
use App\Repository\GrrRoomRepository;
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
     * @var GrrAreaFactory
     */
    private $areaFactory;
    /**
     * @var GrrAreaManager
     */
    private $areaManager;
    /**
     * @var GrrAreaRepository
     */
    private $grrAreaRepository;
    /**
     * @var GrrRoomRepository
     */
    private $grrRoomRepository;

    public function __construct(
        GrrAreaFactory $areaFactory,
        GrrAreaRepository $grrAreaRepository,
        GrrAreaManager $areaManager,
        GrrRoomRepository $grrRoomRepository
    ) {
        $this->areaFactory = $areaFactory;
        $this->areaManager = $areaManager;
        $this->grrAreaRepository = $grrAreaRepository;
        $this->grrRoomRepository = $grrRoomRepository;
    }

    /**
     * @Route("/", name="grr_area_index", methods={"GET"})
     */
    public function index(): Response
    {
        $grrAreas = $this->grrAreaRepository->findAll();

        return $this->render(
            'grr_area/index.html.twig',
            [
                'grr_areas' => $grrAreas,
            ]
        );
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

            $this->areaManager->insert($grrArea);

            return $this->redirectToRoute('grr_area_show', ['id' => $grrArea->getId()]);
        }

        return $this->render(
            'grr_area/new.html.twig',
            [
                'grr_area' => $grrArea,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_area_show", methods={"GET"})
     */
    public function show(GrrArea $grrArea): Response
    {
        $rooms = $this->grrRoomRepository->findBy(['areaId' => $grrArea->getId()]);

        return $this->render(
            'grr_area/show.html.twig',
            [
                'grr_area' => $grrArea,
                'rooms' => $rooms,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="grr_area_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, GrrArea $grrArea): Response
    {
        $form = $this->createForm(GrrAreaType::class, $grrArea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->areaManager->flush();

            return $this->redirectToRoute(
                'grr_area_show',
                [
                    'id' => $grrArea->getId(),
                ]
            );
        }

        return $this->render(
            'grr_area/edit.html.twig',
            [
                'grr_area' => $grrArea,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_area_delete", methods={"DELETE"})
     */
    public function delete(Request $request, GrrArea $grrArea): Response
    {
        if ($this->isCsrfTokenValid('delete'.$grrArea->getId(), $request->request->get('_token'))) {
            $this->areaManager->remove($grrArea);
            $this->areaManager->flush();
        }

        return $this->redirectToRoute('grr_area_index');
    }
}
