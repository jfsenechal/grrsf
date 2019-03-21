<?php

namespace App\Controller;

use App\Entity\Area;
use App\Factory\AreaFactory;
use App\Form\AreaType;
use App\Manager\AreaManager;
use App\Repository\AreaRepository;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/grr/area")
 */
class AreaController extends AbstractController
{
    /**
     * @var AreaFactory
     */
    private $areaFactory;
    /**
     * @var AreaManager
     */
    private $areaManager;
    /**
     * @var AreaRepository
     */
    private $areaRepository;
    /**
     * @var RoomRepository
     */
    private $roomRepository;

    public function __construct(
        AreaFactory $areaFactory,
        AreaRepository $areaRepository,
        AreaManager $areaManager,
        RoomRepository $roomRepository
    ) {
        $this->areaFactory = $areaFactory;
        $this->areaManager = $areaManager;
        $this->areaRepository = $areaRepository;
        $this->roomRepository = $roomRepository;
    }

    /**
     * @Route("/", name="grr_area_index", methods={"GET"})
     */
    public function index(): Response
    {
        $areas = $this->areaRepository->findAll();

        return $this->render(
            'area/index.html.twig',
            [
                'areas' => $areas,
            ]
        );
    }

    /**
     * @Route("/new", name="grr_area_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $area = $this->areaFactory->createNew();
        $this->areaFactory->setDefaultValues($area);

        $form = $this->createForm(AreaType::class, $area);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->areaManager->insert($area);

            return $this->redirectToRoute('grr_area_show', ['id' => $area->getId()]);
        }

        return $this->render(
            'area/new.html.twig',
            [
                'area' => $area,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_area_show", methods={"GET"})
     */
    public function show(Area $area): Response
    {
        $rooms = $this->roomRepository->findBy(['areaId' => $area->getId()]);

        return $this->render(
            'area/show.html.twig',
            [
                'area' => $area,
                'rooms' => $rooms,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="grr_area_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Area $area): Response
    {
        $form = $this->createForm(AreaType::class, $area);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->areaManager->flush();

            return $this->redirectToRoute(
                'grr_area_show',
                [
                    'id' => $area->getId(),
                ]
            );
        }

        return $this->render(
            'area/edit.html.twig',
            [
                'area' => $area,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_area_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Area $area): Response
    {
        if ($this->isCsrfTokenValid('delete'.$area->getId(), $request->request->get('_token'))) {
            $this->areaManager->remove($area);
            $this->areaManager->flush();
        }

        return $this->redirectToRoute('grr_area_index');
    }
}
