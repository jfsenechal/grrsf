<?php

namespace App\Twig;

use App\Entity\Area;
use App\Entity\Entry;
use App\Model\Day;
use App\Model\Month;
use App\Model\RoomModel;
use App\Model\TimeSlot;
use App\Model\Week;
use App\Navigation\MenuGenerator;
use App\Navigation\NavigationManager;
use App\Periodicity\PeriodicityConstant;
use App\Repository\EntryTypeRepository;
use App\Repository\SettingRepository;
use App\Setting\SettingConstants;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GrrFrontExtension extends AbstractExtension
{
    /**
     * @var Environment
     */
    private $twigEnvironment;
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var NavigationManager
     */
    private $navigationManager;
    /**
     * @var MenuGenerator
     */
    private $menuGenerator;
    /**
     * @var EntryTypeRepository
     */
    private $entryTypeRepository;
    /**
     * @var SettingRepository
     */
    private $settingRepository;

    public function __construct(
        RequestStack $requestStack,
        MenuGenerator $menuGenerator,
        Environment $twigEnvironment,
        NavigationManager $navigationManager,
        EntryTypeRepository $entryTypeRepository,
        SettingRepository $settingRepository
    ) {
        $this->twigEnvironment = $twigEnvironment;
        $this->navigationManager = $navigationManager;
        $this->requestStack = $requestStack;
        $this->menuGenerator = $menuGenerator;
        $this->entryTypeRepository = $entryTypeRepository;
        $this->settingRepository = $settingRepository;
    }

    public function getFilters()
    {
        return [
            new TwigFilter(
                'grrPeriodicityTypeName', function (int $type) {
                    return $this->grrPeriodicityTypeName($type);
                }, ['is_safe' => ['html']]
            ),
            new TwigFilter(
                'grrWeekNiceName', function (Week $week) {
                    return $this->grrWeekNiceName($week);
                }, ['is_safe' => ['html']]
            ),
        ];
    }

    /**
     * todo navigation function to same package.
     *
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'grrMonthNavigationRender', function () {
                    return $this->monthNavigationRender();
                }, ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'grrMenuNavigationRender', function () {
                    return $this->menuNavigationRender();
                }, ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'grrGenerateCellDataDay', function (TimeSlot $hour, RoomModel $roomModel, Day $day) {
                    return $this->grrGenerateCellDataDay($hour, $roomModel, $day);
                }, ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'grrLegendEntryType', function (Area $area) {
                    return $this->grrLegendEntryType($area);
                }, ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'grrCompanyName', function () {
                    return $this->grrCompanyName();
                }
            ),
        ];
    }

    /**
     * @param TimeSlot  $hour
     * @param RoomModel $roomModel
     *
     * @return string|void
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function grrGenerateCellDataDay(TimeSlot $hour, RoomModel $roomModel, Day $day)
    {
        /**
         * @var Entry[]
         */
        $entries = $roomModel->getEntries();
        foreach ($entries as $entry) {
            /**
             * @var TimeSlot[]
             */
            $locations = $entry->getLocations();
            $position = 0;
            foreach ($locations as $location) {
                if ($location === $hour) {
                    if (0 === $position) {
                        return $this->twigEnvironment->render(
                            '@grr_front/day/_cell_day_data.html.twig',
                            ['position' => $position, 'entry' => $entry]
                        );
                    }

                    return;
                }
                ++$position;
            }
        }

        $room = $roomModel->getRoom();
        $area = $room->getArea();

        return $this->twigEnvironment->render(
            '@grr_front/day/_cell_day_empty.html.twig',
            ['position' => 999, 'area' => $area, 'room' => $room, 'day' => $day, 'hourModel' => $hour]
        );
    }

    /**
     * @return string
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function monthNavigationRender()
    {
        $request = $this->requestStack->getMasterRequest();

        if (null === $request) {
            return new Response('');
        }

        $year = $request->get('year') ?? 0;
        $month = $request->get('month') ?? 0;

        $monthModel = Month::init($year, $month);

        $navigation = $this->navigationManager->createMonth($monthModel);

        return $this->twigEnvironment->render(
            '@grr_front/navigation/month/_calendar_navigation.html.twig',
            [
                'navigation' => $navigation,
                'monthModel' => $monthModel,
            ]
        );
    }

    /**
     * @return string
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function menuNavigationRender()
    {
        $form = $this->menuGenerator->generateMenuSelect();

        return $this->twigEnvironment->render(
            '@grr_front/navigation/form/_area_form.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    private function grrPeriodicityTypeName(int $type)
    {
        return PeriodicityConstant::getTypePeriodicite($type);
    }

    private function grrWeekNiceName(Week $week)
    {
        return $this->twigEnvironment->render(
            '@grr_front/week/_nice_name.html.twig',
            ['week' => $week]
        );
    }

    private function grrLegendEntryType(Area $area)
    {
        $types = $this->entryTypeRepository->findAll();

        return $this->twigEnvironment->render(
            '@grr_front/_legend_entry_type.html.twig',
            ['types' => $types]
        );
    }

    private function grrCompanyName(): string
    {
        $company = $this->settingRepository->getValueByName(SettingConstants::COMPANY);

        return $company ?? 'Grr';
    }
}
