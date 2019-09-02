<?php
/**
 * This file is part of GrrSf application.
 *
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 20/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Fidry\AliceDataFixtures\LoaderInterface;

class FixtureContext implements Context
{
    /** @var LoaderInterface */
    private $loader;

    /** @var string */
    private $fixturesBasePath;

    /**
     * @var array Will contain all fixtures in an array with the fixture
     *            references as key
     */
    private $fixtures;

    public function __construct(
        Registry $doctrine,
        LoaderInterface $loader,
        string $fixturesBasePath
    ) {
        $this->loader = $loader;
        $this->fixturesBasePath = $fixturesBasePath;

        $this->secure($doctrine);

        /** @var \Doctrine\Common\Persistence\ObjectManager[] $managers */
        $managers = $doctrine->getManagers(); // Note that currently,
        // FidryAliceDataFixturesBundle
        // does not support multiple managers

        foreach ($managers as $manager) {
            if ($manager instanceof \Doctrine\ORM\EntityManagerInterface) {
                $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($manager);
                $schemaTool->dropDatabase();
                $schemaTool->createSchema($manager->getMetadataFactory()->getAllMetadata());
            }
        }
    }

    /**
     * @Given the fixtures file :fixturesFile is loaded
     */
    public function theFixturesFileIsLoaded(string $fixturesFile): void
    {
        $this->fixtures = $this->loader->load([$this->fixturesBasePath.$fixturesFile]);
    }

    private function secure(Registry $doctrine)
    {
        /** @var Connection[] $connections */
        $connections = $doctrine->getConnections();
        foreach ($connections as $connection) {
            if ('pdo_sqlite' !== $connection->getDriver()->getName()) {
                throw new \RuntimeException('Meaningful message here');
            }
        }
    }
}
