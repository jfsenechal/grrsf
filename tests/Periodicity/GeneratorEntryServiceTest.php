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

        $name = 'Entry avec une date en commun';
        $entry = $this->getEntry($name);

        $periodicityDays = $this->entityManager
            ->getRepository(PeriodicityDay::class)
            ->findBy(['entry' => $entry]);

        $entries = $generator->generateEntries($periodicityDays);

        self::assertSame(count($entries), 3);

        foreach ($entries as $entryVirtual) {
            self::assertSame($name, $entryVirtual->getName());
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