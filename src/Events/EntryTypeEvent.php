<?php

namespace App\Events;

use App\Entity\Entry;
use App\Entity\EntryType;
use Symfony\Contracts\EventDispatcher\Event;

class EntryTypeEvent extends Event
{
    const NEW_SUCCESS = 'grr.entry_type.new.success';
    const EDIT_SUCCESS = 'grr.entry_type.edit.success';
    const DELETE_SUCCESS = 'grr.entry_type.delete.success';

    /**
     * @var EntryType
     */
    private $entry_type;

    public function __construct(EntryType $entryType)
    {
        $this->entry_type = $entryType;
    }

    /**
     * @return EntryType
     */
    public function getEntryType(): EntryType
    {
        return $this->entry_type;
    }
}
