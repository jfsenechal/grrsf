<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 23/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Tests\Periodicity;


use App\Entity\PeriodicityDay;
use App\Model\Month;
use App\Periodicity\GeneratorEntry;
use App\Tests\Repository\BaseRepository;

class GeneratorEntryServiceTest extends BaseRepository
{
    public function testGenerate()
    {
        $generator = new GeneratorEntry();

        $this->loadFixtures();

        $monthModel = Month::init(2019, 6);
        $room = $this->getRoom('Digital Room');

        $periodicityDays = $this->entityManager
            ->getRepository(PeriodicityDay::class)
            ->fin($monthModel, $room);

        $entries = $generator->generateEntries($periodicityDays);
        foreach ($entries as $entry) {
            var_dump($entry->getName());
            //    self::assertSame('2019-06-05', $day->getDatePeriodicity()->format('Y-m-d'));
            //  self::assertSame('Tous les mois le 5', $day->getEntry()->getName());
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
}