<?php

namespace App\Controller\Admin;

use App\Entity\EntryType;
use App\Factory\TypeEntryFactory;
use App\Form\TypeEntryType;
use App\Manager\TypeEntryManager;
use App\Repository\EntryTypeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/type/entry")
 * @IsGranted("ROLE_GRR_ADMINISTRATOR")
 */
class TypeEntryController extends AbstractController
{
    /**
     * @var EntryTypeRepository
     */
    private $entryTypeRepository;
    /**
     * @var TypeEntryManager
     */
    private $typeEntryManager;
    /**
     * @var TypeEntryFactory
     */
    private $typeEntryFactory;

    public function __construct(
        TypeEntryFactory $typeEntryFactory,
        EntryTypeRepository $entryTypeRepository,
        TypeEntryManager $typeEntryManager
    ) {
        $this->entryTypeRepository = $entryTypeRepository;
        $this->typeEntryManager = $typeEntryManager;
        $this->typeEntryFactory = $typeEntryFactory;
    }

    /**
     * @Route("/", name="grr_admin_type_entry_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render(
            '@grr_admin/type_entry/index.html.twig',
            [
                'type_entries' => $this->entryTypeRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="grr_admin_type_entry_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $entryType = $this->typeEntryFactory->createNew();

        $form = $this->createForm(TypeEntryType::class, $entryType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entryTypeRepository->insert($entryType);

            return $this->redirectToRoute('grr_admin_type_entry_index');
        }

        return $this->render(
            '@grr_admin/type_entry/new.html.twig',
            [
                'type_entry' => $entryType,
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
        $form = $this->createForm(TypeEntryType::class, $typeArea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entryTypeRepository->flush();

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
    public function delete(Request $request, EntryType $entryType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entryType->getId(), $request->request->get('_token'))) {
            $this->entryTypeRepository->persist($entryType);
            $this->entryTypeRepository->flush();
        }

        return $this->redirectToRoute('grr_admin_type_entry_index');
    }
}
