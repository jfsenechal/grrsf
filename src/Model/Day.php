<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 20/03/19
 * Time: 17:14
 */

namespace App\Model;


use App\Entity\Entry;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Day extends CarbonImmutable
{
    /**
     * @var ArrayCollection|Entry[] $entries
     */
    protected $entries;

    /**
     * Day constructor.
     * @param null $time
     * @param null $tz
     * @throws \Exception Emits Exception in case of an error
     */
    public function __construct($time = null, $tz = null)
    {
        parent::__construct($time, $tz);
        $this->entries = new ArrayCollection();
    }

    /**
     * @return Collection|Entry[]
     */
    public function getEntries(): Collection
    {
        return $this->entries;
    }

    public function addEntry(Entry $entry): self
    {
        if (!$this->entries->contains($entry)) {
            $this->entries[] = $entry;
        }

        return $this;
    }

    public function addEntries(array $entries)
    {
        foreach ($entries as $entry) {
            $this->addEntry($entry);
        }
    }

    public function time(): string
    {
        return $this->dateImmutable->format('h:s');
    }

}