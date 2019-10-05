<?php
/**
 * This file is part of GrrSf application.
 *
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 23/09/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;

abstract class BaseManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    public function persist(object $object)
    {
        $this->entityManager->persist($object);
    }

    public function remove(object $object)
    {
        $this->entityManager->remove($object);
    }

    public function flush()
    {
        $this->entityManager->flush();
    }

    public function insert(object $object)
    {
        $this->entityManager->persist($object);
        $this->flush();
    }
}
