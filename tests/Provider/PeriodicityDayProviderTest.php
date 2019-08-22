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
        var_dump($periodicity->getType());
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

        $result = ['2019-06-12'];

        foreach ($days as $day) {
            //  var_dump($day->toDateString());
            self::assertContains($day->toDateString(), $result);
        }
    }

    /**
     * Test repeat every year
     */
    public function te2stGetDaysByPeriodicityRepeatByYear()
    {
        $this->loadFixtures();
        $periodicity = $this->getPeriodicity(PeriodicityConstant::EVERY_YEAR);
        $entry = $this->getEntry('Tous les ans le 4 octobre');
        $periodicityDayProvider = $this->initPeriodicityDayProvier();

        $days = $periodicityDayProvider->getDaysByPeriodicity($periodicity, $entry->getStartTime());

        $result = ['2019-12-03', '2019-12-04', '2019-12-05'];

        foreach ($days as $day) {
            var_dump($day->toDateString());
            self::assertContains($day->toDateString(), $result);
        }
    }

    /**
     * Test repeat every week
     */
    public function te2stGetDaysByPeriodicityRepeatByEveryWeek1()
    {
        $this->loadFixtures();
        $periodicity = $this->getPeriodicity(PeriodicityConstant::EVERY_WEEK);
        $entry = $this->getEntry('Toutes les semaines, lundi et mardi');
        $periodicityDayProvider = $this->initPeriodicityDayProvier();

        $days = $periodicityDayProvider->getDaysByPeriodicity($periodicity, $entry->getStartTime());

        $result = ['2019-12-03', '2019-12-04', '2019-12-05'];

        foreach ($days as $day) {
            self::assertContains($day->toDateString(), $result);
        }
    }

    /**
     * Test repeat every 2 week
     */
    public function te2stGetDaysByPeriodicityRepeatByEveryWeek2()
    {
        $this->loadFixtures();
        $periodicity = $this->getPeriodicity(PeriodicityConstant::EVERY_WEEK);
        $entry = $this->getEntry('Toutes les 2 semaines, lundi mercredi et samedi');
        $periodicityDayProvider = $this->initPeriodicityDayProvier();

        $days = $periodicityDayProvider->getDaysByPeriodicity($periodicity, $entry->getStartTime());

        $result = ['2019-12-03', '2019-12-04', '2019-12-05'];

        foreach ($days as $day) {
            self::assertContains($day->toDateString(), $result);
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