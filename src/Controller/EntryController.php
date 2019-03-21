<?php

namespace App\Controller;

use App\Entity\Entry;
use App\Factory\EntryFactory;
use App\Form\EntryType;
use App\Form\SearchEntryType;
use App\Manager\EntryManager;
use App\Repository\EntryRepository;
use App\Repository\RepeatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/grr/entry")
 */
class EntryController extends AbstractController
{
    /**
     * @var EntryRepository
     */
    private $entryRepository;
    /**
     * @var RepeatRepository
     */
    private $repeatRepository;
    /**
     * @var EntryManager
     */
    private $entryManager;
    /**
     * @var EntryFactory
     */
    private $entryFactory;

    public function __construct(
        EntryFactory $entryFactory,
        EntryRepository $entryRepository,
        EntryManager $entryManager,
        RepeatRepository $repeatRepository
    ) {
        $this->entryRepository = $entryRepository;
        $this->repeatRepository = $repeatRepository;
        $this->entryManager = $entryManager;
        $this->entryFactory = $entryFactory;
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

        $entries = $this->entryRepository->search($args);

        return $this->render(
            'entry/index.html.twig',
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
        $entry = $this->entryFactory->createNew();

        $form = $this->createForm(EntryType::class, $entry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entry->setTimestamp(new \DateTime());
            $this->entryManager->insert($entry);

            return $this->redirectToRoute('grr_entry_index');
        }

        return $this->render(
            'entry/new.html.twig',
            [
                'entry' => $entry,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_entry_show", methods={"GET"})
     */
    public function show(Entry $entry): Response
    {
        $repeat = '';
        if ($entry->getRepeatId()) {
            $repeat = $this->repeatRepository->find($entry->getRepeatId());
        }

        return $this->render(
            'entry/show.html.twig',
            [
                'entry' => $entry,
                'repeat' => $repeat,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="grr_entry_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Entry $entry): Response
    {
        $form = $this->createForm(EntryType::class, $entry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entryManager->flush();

            return $this->redirectToRoute(
                'grr_entry_index',
                [
                    'id' => $entry->getId(),
                ]
            );
        }

        return $this->render(
            'entry/edit.html.twig',
            [
                'entry' => $entry,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_entry_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Entry $entry): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entry->getId(), $request->request->get('_token'))) {
            $this->entryManager->remove($entry);
            $this->entryManager->flush();
        }

        return $this->redirectToRoute('grr_entry_index');
    }
}
