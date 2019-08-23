<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 23/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Tests\Service;


use App\Entity\Entry;
use App\Entity\PeriodicityDay;
use App\Factory\CarbonFactory;
use App\Factory\DayFactory;
use App\Helper\LocalHelper;
use App\Model\Month;
use App\Model\Week;
use App\Periodicity\GeneratorEntry;
use App\Provider\TimeSlotsProvider;
use App\Service\BindDataManager;
use App\Service\EntryLocationService;
use App\Tests\Repository\BaseRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BindDataManagerTest extends BaseRepository
{
    public function testBindMonthWithRoom()
    {
        $this->loadFixtures();

        $bindDataManager = $this->initBindDataManager();

        $monthModel = Month::init(2019, 12);
        $area = $this->getArea('Esquare');
        $room = $this->getRoom('Relax Room');

        $bindDataManager->bindMonth($monthModel, $area, $room);

        self::assertCount(31, $monthModel->getDataDays());
        foreach ($monthModel->getDataDays() as $dataDay) {
            self::assertCount($this->getCountEntriesFoMonthWithRoom($dataDay->day), $dataDay->getEntries());
            foreach ($dataDay->getEntries() as $entry) {
                self::assertContains($entry->getName(), $this->resultNamesMonthWithRoom());
            }
        }
    }

    public function testBindMonthWithOutRoom()
    {
        $this->loadFixtures();

        $bindDataManager = $this->initBindDataManager();

        $monthModel = Month::init(2019, 12);
        $area = $this->getArea('Esquare');

        $bindDataManager->bindMonth($monthModel, $area, null);

        self::assertCount(31, $monthModel->getDataDays());
        foreach ($monthModel->getDataDays() as $dataDay) {
            self::assertCount($this->getCountEntriesFoMonthWithOutRoom($dataDay->day), $dataDay->getEntries());
            foreach ($dataDay->getEntries() as $entry) {
                self::assertContains($entry->getName(), $this->resultNamesMonthWithOutRoom());
            }
        }
    }

    public function stBindWeek()
    {
        $this->loadFixtures();

        $bindDataManager = $this->initBindDataManager();

        $monthModel = Week::create(2019, 12);
        $area = $this->getArea('Esquare');
        $room = $this->getRoom('Relax Room');

        $bindDataManager->bindWeek($monthModel, $area, $room);

        foreach ($monthModel->getDataDays() as $dataDay) {
            self::assertCount(3, $dataDay->getEntries());
            foreach ($dataDay->getEntries() as $entry) {
                self::assertContains($entry->getName(), $this->resultNamesMonthWithRoom());
            }
        }
    }

    protected function loadFixtures()
    {
        $files =
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
                $this->pathFixtures.'entry_type.yaml',
                $this->pathFixtures.'entry_with_periodicity.yaml',
                $this->pathFixtures.'periodicity_day.yaml',
            ];

        $this->loader->load($files);
    }

    protected function initTimeSlotProvider(): TimeSlotsProvider
    {
        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $localHelper = new LocalHelper($parameterBag);
        $carbonFactory = new CarbonFactory($localHelper);

        return new TimeSlotsProvider($carbonFactory);
    }

    protected function initDayFactory(): DayFactory
    {
        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $localHelper = new LocalHelper($parameterBag);
        $carbonFactory = new CarbonFactory($localHelper);

        return new DayFactory($carbonFactory);
    }

    private function initLocationService(): EntryLocationService
    {
        return new EntryLocationService($this->initTimeSlotProvider());
    }

    private function initGeneratorFactory(): GeneratorEntry
    {
        return new GeneratorEntry();
    }

    private function initBindDataManager(): BindDataManager
    {
        $entryRepository = $this->entityManager->getRepository(Entry::class);
        $periodicityDayRepository = $this->entityManager->getRepository(PeriodicityDay::class);
        $dayFactory = $this->initDayFactory();
        $entryLocationService = $this->initLocationService();
        $generatorEntry = $this->initGeneratorFactory();

        return new BindDataManager(
            $entryRepository,
            $periodicityDayRepository,
            $entryLocationService,
            $generatorEntry,
            $dayFactory
        );

    }

    private function resultNamesMonthWithRoom()
    {
        return [
            "Tous les jours pendant 3 jours",
            "Entry avec une date en commun",
        ];
    }

    private function resultNamesMonthWithOutRoom()
    {
        return [
            "Entry avec une date en commun",
            "Tous les jours pendant 3 jours",
            "Entry avec une date en commun",
        ];
    }


    protected function getCountEntriesFoMonthWithRoom(int $day): int
    {
        $result = [3 => 1, 4 => 1, 5 => 1, 6 => 1,];

        return $result[$day] ?? 0;
    }

    protected function getCountEntriesFoMonthWithOutRoom(int $day): int
    {
        $result = [3 => 2, 4 => 1, 5 => 1, 6 => 1];

        return $result[$day] ?? 0;
    }

}