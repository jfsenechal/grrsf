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
use App\Entity\Room;
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
     * @var string
     */
    protected $pathFixtures;

    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface
     */
    private $kernel2;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->kernel2 = self::bootKernel();

        $this->entityManager = $this->kernel2->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->pathFixtures = $this->kernel2->getProjectDir().'/src/DataFixtures/';
        $this->loader = $this->kernel2->getContainer()->get('fidry_alice_data_fixtures.loader.doctrine');

        parent::setUp();
        //    $this->truncateEntities();
    }

    protected function getRoom(string $roomName): Room
    {
        return $this->entityManager
            ->getRepository(Room::class)
            ->findOneBy(['name' => $roomName]);
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

        $this->kernel2->shutdown();
        $this->kernel2 = null;

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}