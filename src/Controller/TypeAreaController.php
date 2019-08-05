<?php

namespace App\Controller;

use App\Entity\TypeArea;
use App\Factory\TypeEntryFactory;
use App\Form\TypeAreaType;
use App\Manager\TypeAreaManager;
use App\Repository\TypeAreaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/grr/type/area")
 */
class TypeAreaController extends AbstractController
{
    /**
     * @var TypeEntryFactory
     */
    private $typeAreaFactory;
    /**
     * @var TypeAreaRepository
     */
    private $typeAreaRepository;
    /**
     * @var TypeAreaManager
     */
    private $typeAreaManager;

    public function __construct(
        TypeEntryFactory $typeAreaFactory,
        TypeAreaRepository $typeAreaRepository,
        TypeAreaManager $typeAreaManager
    ) {
        $this->typeAreaFactory = $typeAreaFactory;
        $this->typeAreaRepository = $typeAreaRepository;
        $this->typeAreaManager = $typeAreaManager;
    }

    /**
     * @Route("/", name="grr_type_area_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render(
            'type_area/index.html.twig',
            [
                'type_areas' => $this->typeAreaRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="grr_type_area_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $typeArea = $this->typeAreaFactory->createNew();
        $this->typeAreaFactory->setDefaultValues($typeArea);

        $form = $this->createForm(TypeAreaType::class, $typeArea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->typeAreaRepository->insert($typeArea);

            return $this->redirectToRoute('grr_type_area_index');
        }

        return $this->render(
            'type_area/new.html.twig',
            [
                'type_area' => $typeArea,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_type_area_show", methods={"GET"})
     */
    public function show(TypeArea $typeArea): Response
    {
        return $this->render(
            'type_area/show.html.twig',
            [
                'type_area' => $typeArea,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="grr_type_area_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TypeArea $typeArea): Response
    {
        $form = $this->createForm(TypeAreaType::class, $typeArea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->typeAreaRepository->flush();

            return $this->redirectToRoute(
                'grr_type_area_index',
                [
                    'id' => $typeArea->getId(),
                ]
            );
        }

        return $this->render(
            'type_area/edit.html.twig',
            [
                'type_area' => $typeArea,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_type_area_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TypeArea $typeArea): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeArea->getId(), $request->request->get('_token'))) {
            $this->typeAreaRepository->persist($typeArea);
            $this->typeAreaRepository->flush();
        }

        return $this->redirectToRoute('grr_type_area_index');
    }
}
