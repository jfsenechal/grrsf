<?php

namespace App\Controller\Front;

use App\Entity\Area;
use App\Entity\Room;
use App\Factory\CarbonFactory;
use App\Helper\MonthHelperDataDisplay;
use App\Helper\RessourceSelectedHelper;
use App\Model\Day;
use App\Model\Month;
use App\Model\Week;
use App\Provider\SettingsProvider;
use App\Provider\TimeSlotsProvider;
use App\Service\BindDataManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FrontController.
 *
 * @Route("/front")
 */
class DefaultController extends AbstractController implements FrontControllerInterface
{
    /**
     * @var BindDataManager
     */
    private $calendarDataManager;
    /**
     * @var SettingsProvider
     */
    private $settingservice;
    /**
     * @var MonthHelperDataDisplay
     */
    private $monthHelperDataDisplay;
    /**
     * @var TimeSlotsProvider
     */
    private $timeSlotsProvider;
    /**
     * @var RessourceSelectedHelper
     */
    private $ressourceSelectedHelper;

    public function __construct(
        SettingsProvider $settingservice,
        MonthHelperDataDisplay $monthHelperDataDisplay,
        BindDataManager $calendarDataManager,
        TimeSlotsProvider $timeSlotsProvider,
        RessourceSelectedHelper $ressourceSelectedHelper
    ) {
        $this->calendarDataManager = $calendarDataManager;
        $this->settingservice = $settingservice;
        $this->monthHelperDataDisplay = $monthHelperDataDisplay;
        $this->timeSlotsProvider = $timeSlotsProvider;
        $this->ressourceSelectedHelper = $ressourceSelectedHelper;
    }

    /**
     * @Route("/", name="grr_front_home", methods={"GET"})
     */
    public function index(): Response
    {
        $today = CarbonFactory::getToday();

        $area = $this->ressourceSelectedHelper->getArea();

        return $this->redirectToRoute(
            'grr_front_month',
            ['area' => $area->getId(), 'year' => $today->year, 'month' => $today->month]
        );
    }

    /**
     * @Route("/monthview/area/{area}/year/{year}/month/{month}/room/{room}", name="grr_front_month", methods={"GET"})
     * @Entity("area", expr="repository.find(area)")
     * @ParamConverter("room", options={"mapping"={"room"="id"}})
     */
    public function month(Area $area, int $year, int $month, Room $room = null): Response
    {
        $monthModel = Month::init($year, $month);
        $this->calendarDataManager->bindMonth($monthModel, $area, $room);

        $monthData = $this->monthHelperDataDisplay->generateHtmlMonth($monthModel);

        return $this->render(
            '@grr_front/month/month.html.twig',
            [
                'firstDay' => $monthModel->firstOfMonth(),
                'area' => $area,
                'room' => $room,
                'data' => $monthData,
            ]
        );
    }

    /**
     * @Route("/weekview/area/{area}/year/{year}/month/{month}/week/{week}/room/{room}", name="grr_front_week", methods={"GET"})
     * @Entity("area", expr="repository.find(area)")
     * @ParamConverter("room", options={"mapping"={"room"="id"}})
     */
    public function week(Area $area, int $year, int $month, int $week, Room $room = null): Response
    {
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
     * @ParamConverter("room", options={"mapping"={"room"="id"}})
     */
    public function day(Area $area, int $year, int $month, int $day, Room $room = null): Response
    {
        $daySelected = CarbonFactory::createImmutable($year, $month, $day);
        $dayModel = new Day($daySelected);

        if ($room) {
            $rooms = [$room];
        }

        $hoursModel = $this->timeSlotsProvider->getTimeSlotsModelByAreaAndDay($area, $daySelected);
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
