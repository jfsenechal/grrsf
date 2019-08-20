<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 20/03/19
 * Time: 17:14.
 */

namespace App\Model;

use App\Entity\Entry;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Day extends CarbonImmutable
{
    /**
     * @var ArrayCollection|Entry[]
     */
    protected $entries;

    public function construct($time = null, $tz = null)
    {
        dump(123);
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

    /**
     * @param Entry[]|ArrayCollection $entries
     * @return Day
     */
    public function setEntries($entries): self
    {
        $this->entries = $entries;

        return $this;
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
