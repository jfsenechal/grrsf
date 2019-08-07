<?php

namespace App\Controller\Admin;

use App\Entity\Area;
use App\Entity\Entry;
use App\Entity\Room;
use App\Factory\EntryFactory;
use App\Form\EntryType;
use App\Form\PeriodicityType;
use App\Form\SearchEntryType;
use App\Manager\EntryManager;
use App\Repository\EntryRepository;
use App\Repository\RepeatRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/entry")
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
     * @Route("/", name="grr_admin_entry_index", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
        $args = $entries = [];

        $form = $this->createForm(SearchEntryType::class, $args);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entries = $this->entryRepository->search($args);

        }

        return $this->render(
            '@grr_admin/entry/index.html.twig',
            [
                'entries' => $entries,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/new/area/{area}/room/{room}/year/{year}/month/{month}/day/{day}", name="grr_admin_entry_new", methods={"GET","POST"})
     * @Entity("area", expr="repository.find(area)")
     * @Entity("room", expr="repository.find(room)")
     */
    public function new(Request $request, Area $area, Room $room, int $year, int $month, int $day): Response
    {
        $entry = EntryFactory::createNew();
        EntryFactory::setDefaultValues($entry);
        $entry->setArea($area);

        if ($room) {
            $entry->setRoom($room);
        }

        $form = $this->createForm(EntryType::class, $entry);
        $formPeriodicity = $this->createForm(PeriodicityType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entry->setTimestamp(new \DateTime());
            $this->entryManager->insert($entry);

            return $this->redirectToRoute('grr_admin_entry_index');
        }

        return $this->render(
            '@grr_admin/entry/new.html.twig',
            [
                'entry' => $entry,
                'form_periodicity' => $formPeriodicity->createView(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_admin_entry_show", methods={"GET"})
     */
    public function show(Entry $entry): Response
    {
        $repeat = '';
        if ($entry->getRepeatId()) {
            $repeat = $this->repeatRepository->find($entry->getRepeatId());
        }

        return $this->render(
            '@grr_admin/entry/show.html.twig',
            [
                'entry' => $entry,
                'repeat' => $repeat,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="grr_admin_entry_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Entry $entry): Response
    {
        $form = $this->createForm(EntryType::class, $entry);
        $formPeriodicity = $this->createForm(PeriodicityType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entryManager->flush();

            return $this->redirectToRoute(
                'grr_admin_entry_index',
                [
                    'id' => $entry->getId(),
                ]
            );
        }

        return $this->render(
            '@grr_admin/entry/edit.html.twig',
            [
                'entry' => $entry,
                'form' => $form->createView(),
                'form_periodicity' => $formPeriodicity->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_admin_entry_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Entry $entry): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entry->getId(), $request->request->get('_token'))) {
            $this->entryManager->remove($entry);
            $this->entryManager->flush();
        }

        return $this->redirectToRoute('grr_admin_entry_index');
    }
}
