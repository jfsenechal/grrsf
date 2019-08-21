<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 20/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Tests\Repository;


use App\Entity\Area;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Fidry\AliceDataFixtures\LoaderInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseRepository extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /** @var LoaderInterface */
    protected $loader;
    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface
     */
    protected $kernel;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->kernel = self::bootKernel();

        $this->entityManager = $this->kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->loader = $this->kernel->getContainer()->get('fidry_alice_data_fixtures.loader.doctrine');
        //    $this->truncateEntities();
    }

    private function truncateEntities()
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $purger = new ORMPurger($this->entityManager);
        $purger->purge();

        $this->kernel->shutdown();
        $this->kernel = null;

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}