<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 20/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Tests\Provider;


use App\Entity\Area;
use App\Entity\Entry;
use App\Entity\Periodicity;
use App\Entity\Room;
use App\Factory\CarbonFactory;
use App\GrrData\PeriodicityConstant;
use App\Helper\LocalHelper;
use App\Provider\PeriodicityDaysProvider;
use App\Provider\TimeSlotsProvider;
use App\Tests\Repository\BaseRepository;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PeriodicityDayProviderTest extends BaseRepository
{
    /**
     * Test repeat every day
     */
    public function testGetDaysByPeriodicityRepeatByDay()
    {
        $this->loadFixtures();
        $periodicity = $this->getPeriodicity(PeriodicityConstant::EVERY_DAY);
        $entry = $this->getEntry('Tous les jours pendant 3 jours');
        $periodicityDayProvider = $this->initPeriodicityDayProvier();

        $days = $periodicityDayProvider->getDaysByPeriodicity($periodicity, $entry->getStartTime());

        $result = ['2019-12-03', '2019-12-04', '2019-12-05'];

        foreach ($days as $day) {
            self::assertContains($day->toDateString(), $result);
        }
    }

    /**
     * Test repeat every month same day
     */
    public function testGetDaysByPeriodicityRepeatByMonthSameDay()
    {
        $this->loadFixtures();
        $periodicity = $this->getPeriodicity(PeriodicityConstant::EVERY_MONTH_SAME_DAY);
        $entry = $this->getEntry('Tous les mois le 5');
        $periodicityDayProvider = $this->initPeriodicityDayProvier();

        $days = $periodicityDayProvider->getDaysByPeriodicity($periodicity, $entry->getStartTime());

        $result = ['2019-04-05', '2019-05-05', '2019-06-05', '2019-07-05'];

        foreach ($days as $day) {
            self::assertContains($day->toDateString(), $result);
        }
    }

    /**
     * Test repeat every month same week day
     */
    public function testGetDaysByPeriodicityRepeatByMonthSameWeekday()
    {
        $this->loadFixtures();
        $periodicity = $this->getPeriodicity(PeriodicityConstant::EVERY_MONTH_SAME_WEEK_DAY);
        $entry = $this->getEntry('Tous les mois le 2ième mercredi');
        $periodicityDayProvider = $this->initPeriodicityDayProvier();

        $days = $periodicityDayProvider->getDaysByPeriodicity($periodicity, $entry->getStartTime());

        $result = ['2019-06-12', '2019-07-10', '2019-08-14', '2019-09-11', '2019-10-09'];

        foreach ($days as $day) {
            self::assertContains($day->toDateString(), $result);
        }
    }

    /**
     * Test repeat every year
     */
    public function testGetDaysByPeriodicityRepeatByYear()
    {
        $this->loadFixtures();
        $periodicity = $this->getPeriodicity(PeriodicityConstant::EVERY_YEAR);
        $entry = $this->getEntry('Tous les ans le 4 octobre');
        $periodicityDayProvider = $this->initPeriodicityDayProvier();

        $days = $periodicityDayProvider->getDaysByPeriodicity($periodicity, $entry->getStartTime());

        $result = ['2020-10-04', '2021-10-04', '2022-10-04'];

        foreach ($days as $day) {
            self::assertContains($day->toDateString(), $result);
        }
    }

    /**
     * Test repeat every week
     */
    public function testGetDaysByPeriodicityRepeatByEveryWeek1()
    {
        $this->loadFixtures();
        $periodicity = $this->getPeriodicity(PeriodicityConstant::EVERY_WEEK);
        $entry = $this->getEntry('Toutes les semaines, lundi et mardi');
        $periodicityDayProvider = $this->initPeriodicityDayProvier();

        $days = $periodicityDayProvider->getDaysByPeriodicity($periodicity, $entry->getStartTime());

        $result = [
            "2018-06-25",
            "2018-06-26",
            "2018-07-02",
            "2018-07-03",
            "2018-07-09",
            "2018-07-10",
            "2018-07-16",
            "2018-07-17",
            "2018-07-23",
            "2018-07-24",
            "2018-07-30",
            "2018-07-31",
            "2018-08-06",
            "2018-08-07",
            "2018-08-13",
            "2018-08-14",
            "2018-08-20",
        ];

        foreach ($days as $day) {
            self::assertContains($day->toDateString(), $result);
        }
    }

    /**
     * Test repeat every 2 week
     */
    public function testGetDaysByPeriodicityRepeatByEveryWeek2()
    {
        $this->loadFixtures();
        $periodicity = $this->getPeriodicity(PeriodicityConstant::EVERY_WEEK);
        $entry = $this->getEntry('Toutes les 2 semaines, mercredi et samedi');
        $periodicityDayProvider = $this->initPeriodicityDayProvier();

        $days = $periodicityDayProvider->getDaysByPeriodicity($periodicity, $entry->getStartTime());
        $result = [];

        foreach ($days as $day) {
            var_dump($day->toDateString());
            //    self::assertContains($day->toDateString(), $result);
        }
    }

    protected function initPeriodicityDayProvier(): PeriodicityDaysProvider
    {
        $parameterBag = $this->createMock(ParameterBagInterface::class);

        return new PeriodicityDaysProvider();
    }

    protected function loadFixtures()
    {
        $this->loader->load(
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
                $this->pathFixtures.'entry_type.yaml',
                $this->pathFixtures.'entry_with_periodicity.yaml',
                $this->pathFixtures.'periodicity.yaml',
            ]
        );
    }

    protected function getPeriodicity(int $type): Periodicity
    {
        return $this->entityManager
            ->getRepository(Periodicity::class)
            ->findOneBy(['type' => $type]);
    }

    protected function getEntry(string $name): Entry
    {
        return $this->entityManager
            ->getRepository(Entry::class)
            ->findOneBy(['name' => $name]);
    }

}