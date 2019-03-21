<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 20/03/19
 * Time: 17:14
 */

namespace App\Model;


class Day
{
    /**
     * @var \DateTimeInterface $date
     */
    protected $date;

    /**
     * @var array $entries
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
     * @param \DateTimeInterface $date
     * @return Day
     */
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return array
     */
    public function getEntries(): array
    {
        return $this->entries;
    }

    /**
     * @param array $entries
     * @return Day
     */
    public function setEntries(array $entries): self
    {
        $this->entries = $entries;

        return $this;
    }

}