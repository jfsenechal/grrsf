<?php


namespace App\Events;


use App\Entity\Entry;
use Symfony\Contracts\EventDispatcher\Event;

class EntryEvent extends Event
{
    const ENTRY_BEFORE_NEW = 'grr.entry_before_new';
    const ENTRY_CREATED = 'grr.entry_created';
    const ENTRY_UPDATED = 'grr.entry_updated';

    /**
     * @var Entry
     */
    private $entry;

    public function __construct(Entry $entry)
    {
        $this->entry = $entry;
    }

    /**
     * @return Entry
     */
    public function getEntry(): Entry
    {
        return $this->entry;
    }

}