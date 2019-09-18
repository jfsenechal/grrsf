<?php
/**
 * This file is part of GrrSf application.
 *
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 23/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Periodicity;

use App\Entity\PeriodicityDay;
use App\Periodicity\GeneratorEntry;
use App\Tests\BaseTesting;

class GeneratorEntryServiceTest extends BaseTesting
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

        $entries = $generator->generateEntry($periodicityDays);

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
