<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 21/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Tests\Command;

use App\Tests\Repository\BaseRepository;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class InstallDataCommandTest extends BaseRepository
{
    public function testExecute()
    {
        $this->loadFixtures();

        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('grr:install-data');
        $commandTester = new CommandTester($command);

        $commandTester->execute(
            [
                'command' => $command->getName(),
            ]
        );

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains("Les données ont bien été initialisées.", $output);
    }

    protected function loadFixtures()
    {
        $files = [];

        $this->loader->load($files);
    }
}