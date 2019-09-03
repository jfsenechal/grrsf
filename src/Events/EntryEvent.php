<?php

namespace App\Events;

use App\Entity\Entry;
use Symfony\Contracts\EventDispatcher\Event;

class EntryEvent extends Event
{
    const ENTRY_NEW_INITIALIZE = 'grr.entry.new.initialize';
    const ENTRY_NEW_SUCCESS = 'grr.entry.new.success';
    const ENTRY_NEW_COMPLETE = 'grr.entry.new.complete';
    const ENTRY_EDIT_SUCCESS = 'grr.entry.edit.success';
    const ENTRY_DELETE_SUCCESS = 'grr.entry.delete.success';

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
