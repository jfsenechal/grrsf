<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 20/03/19
 * Time: 17:14
 */

namespace App\Model;


use App\Entity\Entry;

class Day
{
    /**
     * @var \DateTimeInterface $date
     */
    protected $date;

    /**
     * @var Entry[] $entries
     */
    protected $entries;

    public function __construct(\DateTimeInterface $dateTime)
    {
        $this->date = $dateTime;
        $this->entries = [];
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @return Entry[]
     */
    public function getEntries(): array
    {
        return $this->entries;
    }

    /**
     * @param Entry[] $entries
     * @return Day
     */
    public function setEntries(array $entries): self
    {
        $this->entries = $entries;

        return $this;
    }

    public function time(): string
    {
        return $this->date->format('h:s');
    }

    public function year(): string
    {
        return $this->date->format('Y');
    }

    public function month(): string
    {
        return $this->date->format('m');
    }

}