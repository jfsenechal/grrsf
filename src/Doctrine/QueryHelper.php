<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 20/03/19
 * Time: 16:38.
 */

namespace App\Doctrine;

use Doctrine\ORM\QueryBuilder;

class QueryHelper
{
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    public function addConstraint(\DateTimeInterface $dateTime)
    {
        $this->queryBuilder->andWhere('entry.startTime LIKE %:date%')
            ->setParameter('date', $dateTime);
    }
}
