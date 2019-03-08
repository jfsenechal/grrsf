<?php

namespace App\Controller;

use App\Entity\GrrEntry;
use App\Factory\GrrEntryFactory;
use App\Form\GrrEntryType;
use App\Form\SearchEntryType;
use App\Manager\GrrEntryManager;
use App\Repository\GrrEntryRepository;
use App\Repository\GrrRepeatRepository;
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
     * @var GrrEntryRepository
     */
    private $grrEntryRepository;
    /**
     * @var GrrRepeatRepository
     */
    private $grrRepeatRepository;
    /**
     * @var GrrEntryManager
     */
    private $grrEntryManager;
    /**
     * @var GrrEntryFactory
     */
    private $grrEntryFactory;

    public function __construct(
        GrrEntryFactory $grrEntryFactory,
        GrrEntryRepository $grrEntryRepository,
        GrrEntryManager $grrEntryManager,
        GrrRepeatRepository $grrRepeatRepository
    ) {
        $this->grrEntryRepository = $grrEntryRepository;
        $this->grrRepeatRepository = $grrRepeatRepository;
        $this->grrEntryManager = $grrEntryManager;
        $this->grrEntryFactory = $grrEntryFactory;
    }

    /**
     * @Route("/", name="grr_entry_index", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
        $args = [];
        $form = $this->createForm(SearchEntryType::class, $args);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $args = $form->getData();
        }

        $entries = $this->grrEntryRepository->search($args);

        return $this->render(
            'grr_entry/index.html.twig',
            [
                'entries' => $entries,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/new", name="grr_entry_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $grrEntry = $this->grrEntryFactory->createNew();
        $form = $this->createForm(GrrEntryType::class, $grrEntry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->grrEntryManager->insert($grrEntry);

            return $this->redirectToRoute('grr_entry_index');
        }

        return $this->render(
            'grr_entry/new.html.twig',
            [
                'grr_entry' => $grrEntry,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_entry_show", methods={"GET"})
     */
    public function show(GrrEntry $grrEntry): Response
    {
        $grr_repeat = $this->grrRepeatRepository->find($grrEntry->getRepeatId());

        return $this->render(
            'grr_entry/show.html.twig',
            [
                'grr_entry' => $grrEntry,
                'grr_repeat' => $grr_repeat,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="grr_entry_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, GrrEntry $grrEntry): Response
    {
        $form = $this->createForm(GrrEntryType::class, $grrEntry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->grrEntryManager->flush();

            return $this->redirectToRoute(
                'grr_entry_index',
                [
                    'id' => $grrEntry->getId(),
                ]
            );
        }

        return $this->render(
            'grr_entry/edit.html.twig',
            [
                'grr_entry' => $grrEntry,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_entry_delete", methods={"DELETE"})
     */
    public function delete(Request $request, GrrEntry $grrEntry): Response
    {
        if ($this->isCsrfTokenValid('delete'.$grrEntry->getId(), $request->request->get('_token'))) {
            $this->grrEntryManager->remove($grrEntry);
            $this->grrEntryManager->flush();
        }

        return $this->redirectToRoute('grr_entry_index');
    }
}
