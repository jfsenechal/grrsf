<?php

namespace App\Events;

use App\Entity\Entry;
use Symfony\Contracts\EventDispatcher\Event;

class EntryEvent extends Event
{
    const NEW_INITIALIZE = 'grr.entry.new.initialize';
    const NEW_SUCCESS = 'grr.entry.new.success';
    const EDIT_SUCCESS = 'grr.entry.edit.success';
    const DELETE_SUCCESS = 'grr.entry.delete.success';

    /**
     * @var Entry
     */
    private $entry;

    public function __construct(Entry $entry)
    {
        $this->entry = $entry;
    }

    public function getEntry(): Entry
    {
        return $this->entry;
    }
}
