<?php

namespace App\Controller\Admin;

use App\Entity\EntryType;
use App\Factory\TypeEntryFactory;
use App\Form\TypeAreaType;
use App\Manager\TypeAreaManager;
use App\Repository\EntryTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/type/entry")
 */
class TypeEntryController extends AbstractController
{
    /**
     * @var EntryTypeRepository
     */
    private $typeAreaRepository;
    /**
     * @var TypeAreaManager
     */
    private $typeAreaManager;

    public function __construct(
        EntryTypeRepository $typeAreaRepository,
        TypeAreaManager $typeAreaManager
    ) {
        $this->typeAreaRepository = $typeAreaRepository;
        $this->typeAreaManager = $typeAreaManager;
    }

    /**
     * @Route("/", name="grr_admin_type_entry_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render(
            '@grr_admin/type_entry/index.html.twig',
            [
                'type_entries' => $this->typeAreaRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="grr_admin_type_entry_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $typeArea = TypeEntryFactory::createNew();
        TypeEntryFactory::setDefaultValues($typeArea);

        $form = $this->createForm(TypeAreaType::class, $typeArea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->typeAreaRepository->insert($typeArea);

            return $this->redirectToRoute('grr_admin_type_entry_index');
        }

        return $this->render(
            '@grr_admin/type_entry/new.html.twig',
            [
                'type_entry' => $typeArea,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_admin_type_entry_show", methods={"GET"})
     */
    public function show(EntryType $typeArea): Response
    {
        return $this->render(
            '@grr_admin/type_entry/show.html.twig',
            [
                'type_entry' => $typeArea,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="grr_admin_type_entry_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EntryType $typeArea): Response
    {
        $form = $this->createForm(TypeAreaType::class, $typeArea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->typeAreaRepository->flush();

            return $this->redirectToRoute(
                'grr_admin_type_entry_index',
                [
                    'id' => $typeArea->getId(),
                ]
            );
        }

        return $this->render(
            '@grr_admin/type_entry/edit.html.twig',
            [
                'type_entry' => $typeArea,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_admin_type_entry_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EntryType $typeArea): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeArea->getId(), $request->request->get('_token'))) {
            $this->typeAreaRepository->persist($typeArea);
            $this->typeAreaRepository->flush();
        }

        return $this->redirectToRoute('grr_admin_type_entry_index');
    }
}
