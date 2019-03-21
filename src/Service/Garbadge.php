<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 20/03/19
 * Time: 17:01
 */

namespace App\Service;


class Garbadge
{
    /**
     * @var array
     */
    protected $entries;

    public function __construct()
    {
        $this->entries = [];
    }

    public function addEntries(\DateTimeInterface $dateTime, iterable $entries)
    {
        $this->entries[$dateTime->format('Y-m-d')] = $entries;
    }

    public function getEntries(\DateTimeInterface $dateTime)
    {
        return $this->entries[$dateTime->format('Y-md')];
    }
}