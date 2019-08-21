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

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CreateUserCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('grr:create-user');
        $commandTester = new CommandTester($command);

        // Equals to a user inputting "Test" and hitting ENTER
        $commandTester->setInputs(['Test']);

        // Equals to a user inputting "This", "That" and hitting ENTER
        // This can be used for answering two separated questions for instance
        $commandTester->setInputs(['This', 'That']);

        // For simulating a positive answer to a confirmation question, adding an
        // additional input saying "yes" will work
        $commandTester->setInputs(['yes']);


        $commandTester->execute(['command' => $command->getName()]);

        $commandTester->execute(
            [
                'command' => $command->getName(),

                // pass arguments to the helper
                'username' => 'Wouter',

                // prefix the key with two dashes when passing options,
                // e.g: '--some-option' => 'option_value',
            ]
        );

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('Username: Wouter', $output);

        // $this->assertRegExp('/.../', $commandTester->getDisplay());
    }
}