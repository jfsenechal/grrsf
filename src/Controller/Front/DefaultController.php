<?php

namespace App\Controller\Front;

use App\Entity\Area;
use App\Factory\CarbonFactory;
use App\Model\Day;
use App\Model\Month;
use App\Model\Week;
use App\Repository\AreaRepository;
use App\Repository\EntryRepository;
use App\Repository\RoomRepository;
use App\Service\AreaService;
use App\Service\BindDataManager;
use App\Helper\MonthHelperDataDisplay;
use App\Service\Settingservice;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FrontController.
 *
 * @Route("/front")
 */
class DefaultController extends AbstractController
{
    /**
     * @var AreaRepository
     */
    private $areaRepository;
    /**
     * @var RoomRepository
     */
    private $roomRepository;
    /**
     * @var EntryRepository
     */
    private $entryRepository;
    /**
     * @var BindDataManager
     */
    private $calendarDataManager;
    /**
     * @var Settingservice
     */
    private $settingservice;
    /**
     * @var AreaService
     */
    private $areaService;
    /**
     * @var MonthHelperDataDisplay
     */
    private $monthHelperDataDisplay;

    public function __construct(
        Settingservice $settingservice,
        MonthHelperDataDisplay $monthHelperDataDisplay,
        AreaRepository $areaRepository,
        RoomRepository $roomRepository,
        EntryRepository $entryRepository,
        BindDataManager $calendarDataManager,
        AreaService $areaService
    ) {
        $this->areaRepository = $areaRepository;
        $this->roomRepository = $roomRepository;
        $this->entryRepository = $entryRepository;
        $this->calendarDataManager = $calendarDataManager;
        $this->settingservice = $settingservice;
        $this->areaService = $areaService;
        $this->monthHelperDataDisplay = $monthHelperDataDisplay;
    }

    /**
     * @Route("/", name="grr_front_home", methods={"GET"})
     */
    public function index(): Response
    {
        $today = CarbonFactory::getToday();

        $esquare = $this->settingservice->getDefaultArea();
        if (!$esquare) {
            return new Response('No data');
        }

        return $this->redirectToRoute(
            'grr_front_month',
            ['area' => $esquare->getId(), 'year' => $today->year, 'month' => $today->month]
        );
    }

    /**
     * @Route("/monthview/area/{area}/year/{year}/month/{month}/room/{room}", name="grr_front_month", methods={"GET"})
     * @Entity("area", expr="repository.find(area)")
     * //prend room = 1 meme si pas selectionne
     * Entity("room", expr="repository.find(room)", isOptional=true, options={"strip_null"=false})
     */
    public function month(Area $area, int $year, int $month, int $room = null): Response
    {
        $roomObject = null;

        if ($room) {
            //todo if room selected
            $roomObject = $this->roomRepository->find($room);
        }

        $monthModel = Month::init($year, $month);
        $this->calendarDataManager->bindMonth($monthModel, $area, $roomObject);

        $monthData = $this->monthHelperDataDisplay->generateHtmlMonth($monthModel);

        return $this->render(
            '@grr_front/month/month.html.twig',
            [
                'firstDay' => $monthModel->firstOfMonth(),
                'area' => $area,
                'room' => $roomObject,
                'data' => $monthData,
            ]
        );
    }

    /**
     * @Route("/weekview/area/{area}/year/{year}/month/{month}/week/{week}/room/{room}", name="grr_front_week", methods={"GET"})
     * @Entity("area", expr="repository.find(area)")
     * Entity("room", expr="repository.find(room)", isOptional=true, options={"strip_null"=true})
     */
    public function week(Area $area, int $year, int $month, int $week, int $room = null): Response
    {
        $roomObject = null;

        if ($room) {
            //todo if room selected
            $roomObject = $this->roomRepository->find($room);
        }

        $weekModel = Week::createWithLocal($year, $week);
        $data = $this->calendarDataManager->bindWeek($weekModel, $area);

        return $this->render(
            '@grr_front/week/week.html.twig',
            [
                'week' => $weekModel,
                'area' => $area, //pour lien add entry
                'data' => $data,
            ]
        );
    }

    /**
     * @Route("/dayview/area/{area}/year/{year}/month/{month}/day/{day}/room/{room}", name="grr_front_day", methods={"GET"})
     * @Entity("area", expr="repository.find(area)")
     * Entity("room", expr="repository.find(room)", isOptional=true, options={"strip_null"=true})
     */
    public function day(Area $area, int $year, int $month, int $day, int $room = null): Response
    {
        $daySelected = CarbonFactory::createImmutable($year, $month, $day);
        $dayModel = new Day($daySelected);

        if ($room) {
            //todo if room selected
            $rooms = [$this->roomRepository->find($room)];
        }

        $hoursModel = $this->areaService->getHoursModel($area, $daySelected);
        $roomsModel = $this->calendarDataManager->bindDay($daySelected, $area, $hoursModel);

        return $this->render(
            '@grr_front/day/day.html.twig',
            [
                'day' => $dayModel,
                'roomsModel' => $roomsModel,
                'area' => $area, //pour lien add entry
                'hoursModel' => $hoursModel,
            ]
        );
    }
}
