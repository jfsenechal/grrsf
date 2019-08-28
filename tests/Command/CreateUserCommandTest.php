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

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateUserCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('grr:create-user');
        $commandTester = new CommandTester($command);

        $commandTester->setInputs(['Y']);

        $commandTester->execute(
            [
                'command' => $command->getName(),

                'email' => 'lolo@domainx.com',
                'name' => 'Wouter',
                'password' => 'mdp1234',
            ]
        );

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains("L'utilisateur a bien été créé", $output);
    }
}