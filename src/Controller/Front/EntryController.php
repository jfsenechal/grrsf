<?php

namespace App\Controller\Front;

use App\Entity\Area;
use App\Entity\Entry;
use App\Entity\Periodicity;
use App\Entity\Room;
use App\Factory\EntryFactory;
use App\Form\EntryType;
use App\Form\SearchEntryType;
use App\GrrData\PeriodicityConstant;
use App\Manager\EntryManager;
use App\Manager\PeriodicityManager;
use App\Repository\EntryRepository;
use App\Service\PeriodicityService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/front/entry")
 */
class EntryController extends AbstractController
{
    /**
     * @var EntryRepository
     */
    private $entryRepository;
    /**
     * @var EntryManager
     */
    private $entryManager;
    /**
     * @var EntryFactory
     */
    private $entryFactory;
    /**
     * @var PeriodicityService
     */
    private $periodicityService;
    /**
     * @var PeriodicityManager
     */
    private $periodicityManager;

    public function __construct(
        EntryFactory $entryFactory,
        EntryRepository $entryRepository,
        EntryManager $entryManager,
        PeriodicityManager $periodicityManager,
        PeriodicityService $periodicityService
    ) {
        $this->entryRepository = $entryRepository;
        $this->entryManager = $entryManager;
        $this->entryFactory = $entryFactory;
        $this->periodicityService = $periodicityService;
        $this->periodicityManager = $periodicityManager;
    }

    /**
     * @Route("/", name="grr_front_entry_index", methods={"GET","POST"})
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
            '@grr_front/entry/index.html.twig',
            [
                'entries' => $entries,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/new/area/{area}/room/{room}/year/{year}/month/{month}/day/{day}/hour/{hour}/minute/{minute}", name="grr_front_entry_new", methods={"GET","POST"})
     * @Entity("area", expr="repository.find(area)")
     * @Entity("room", expr="repository.find(room)")
     */
    public function new(
        Request $request,
        Area $area,
        Room $room,
        int $year,
        int $month,
        int $day,
        int $hour = null,
        int $minute = null
    ): Response {

        $entry = $this->entryFactory->initEntryForNew($area, $room, $year, $month, $day, $hour, $minute);

        $form = $this->createForm(EntryType::class, $entry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            /**
             * @var Periodicity $periodicity
             */
            $periodicity = $data->getPeriodicity();

            if (null === $periodicity->getType()) {
                $entry->setPeriodicity(null);
            }

            $this->entryManager->insert($entry);

            return $this->redirectToRoute(
                'grr_front_entry_show',
                [
                    'id' => $entry->getId(),
                ]
            );
        }

        return $this->render(
            '@grr_front/entry/new.html.twig',
            [
                'entry' => $entry,
                'displayOptionsWeek' => false,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_front_entry_show", methods={"GET"})
     */
    public function show(Entry $entry): Response
    {
        $days = $this->periodicityService->getDays($entry);
        foreach ($days as $day) {
            dump($day);
        }

        return $this->render(
            '@grr_front/entry/show.html.twig',
            [
                'entry' => $entry,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="grr_front_entry_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Entry $entry): Response
    {
        $displayOptionsWeek = false;
        $entry->setArea($entry->getRoom()->getArea());
        $periodicity = $entry->getPeriodicity();

        $typePeriodicity = $periodicity ? $periodicity->getType() : 0;

        if ($typePeriodicity === PeriodicityConstant::EVERY_WEEK) {
            $displayOptionsWeek = true;
        }

        $form = $this->createForm(EntryType::class, $entry);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            dump($typePeriodicity);
            $data = $form->getData();
            $type = $data->getPeriodicity()->getType();
            if (null === $type) {
                $entry->setPeriodicity(null);
                //   $this->periodicityManager->remove($periodicity);
                // $this->periodicityManager->flush();
            }
            $this->entryManager->flush();

            /*    return $this->redirectToRoute(
                    'grr_front_entry_show',
                    ['id' => $entry->getId(),]
                );*/
        }

        return $this->render(
            '@grr_front/entry/edit.html.twig',
            [
                'entry' => $entry,
                'displayOptionsWeek' => $displayOptionsWeek,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="grr_front_entry_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Entry $entry): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entry->getId(), $request->request->get('_token'))) {
            $this->entryManager->remove($entry);
            $this->entryManager->flush();
            $this->addFlash('success', 'flash.entry.delete');
        }

        return $this->redirectToRoute('grr_front_home');
    }
}
