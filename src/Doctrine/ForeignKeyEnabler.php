<?php
/**
 * This file is part of GrrSf application.
 *
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 27/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * We enable foreign keys manually/specifically in the test environment as SQLite does not have them enabled by default.
 *
 * @see https://tomnewby.net/
 */
class ForeignKeyEnabler implements EventSubscriber
{
    /** @var EntityManagerInterface */
    private $manager;
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    public function __construct(EntityManagerInterface $manager, ParameterBagInterface $parameterBag)
    {
        $this->manager = $manager;
        $this->parameterBag = $parameterBag;
    }

    public function getSubscribedEvents()
    {
        $env = $this->parameterBag->get('kernel.environment');
        var_dump($env);

        return [
            'preFlush',
        ];
    }

    public function preFlush(PreFlushEventArgs $args): void
    {
        $this->manager
            ->createNativeQuery('PRAGMA foreign_keys = ON;', new ResultSetMapping())
            ->execute();
    }
}
