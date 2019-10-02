<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 20/03/19
 * Time: 16:21.
 */

namespace App\Navigation;

use App\Model\Month;
use App\Model\Navigation;
use App\Provider\DateProvider;
use Carbon\CarbonInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Webmozart\Assert\Assert;

class NavigationManager
{
    /**
     * @var Environment
     */
    private $twigEnvironment;
    /**
     * @var Month
     */
    private $month;
    /**
     * @var CarbonInterface
     */
    private $today;
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var NavigationFactory
     */
    private $navigationFactory;

    public function __construct(NavigationFactory $navigationFactory, Environment $environment, RequestStack $requestStack)
    {
        $this->twigEnvironment = $environment;
        $this->requestStack = $requestStack;
        $this->navigationFactory = $navigationFactory;
    }

    /**
     * @param Month $month
     * @param int   $number nombre de mois
     *
     * @return Navigation
     */
    public function createMonth(Month $month, int $number = 1): Navigation
    {
        $this->month = $month;

        Assert::greaterThan($number, 0);

        $navigation = $this->navigationFactory->createNew();
        $this->today = $navigation->getToday();

        $navigation->setNextButton($this->nextButtonRender());
        $navigation->setPreviousButton($this->previousButtonRender());

        $current = $this->month->firstOfMonth();

        for ($i = 0; $i < $number; ++$i) {
            $monthModel = Month::init($current->year, $current->month);
            $navigation->addMonth($this->renderMonthByWeeks($monthModel));
            $current->addMonth();
        }

        return $navigation;
    }

    public function previousButtonRender()
    {
        return $this->twigEnvironment->render(
            '@grr_front/navigation/month/_button_previous.html.twig',
            [
                'month' => $this->month,
            ]
        );
    }

    public function nextButtonRender()
    {
        return $this->twigEnvironment->render(
            '@grr_front/navigation/month/_button_next.html.twig',
            [
                'month' => $this->month,
            ]
        );
    }

    public function renderMonthByWeeks(Month $month)
    {
        $firstDay = $month->firstOfMonth();

        $weeks = $this->month->getWeeksOfMonth();
        $request = $this->requestStack->getMasterRequest();
        $weekSelected = null !== $request ? $request->get('week') : 0;
        $daySelected = null !== $request ? $request->get('day') : 0;

        return $this->twigEnvironment->render(
            '@grr_front/navigation/month/_month_by_weeks.html.twig',
            [
                'firstDay' => $firstDay,
                'listDays' => DateProvider::getNamesDaysOfWeek(),
                'weeks' => $weeks,
                'weekSelected' => $weekSelected,
                'daySelected' => $daySelected,
                'today' => $this->today,
            ]
        );
    }
}
