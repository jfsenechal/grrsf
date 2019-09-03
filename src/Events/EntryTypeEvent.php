<?php

namespace App\Events;

use App\Entity\Entry;
use App\Entity\EntryType;
use Symfony\Contracts\EventDispatcher\Event;

class EntryTypeEvent extends Event
{
    const ENTRY_TYPE_NEW_INITIALIZE = 'grr.entry_type.new.initialize';
    const ENTRY_TYPE_NEW_SUCCESS = 'grr.entry_type.new.success';
    const ENTRY_TYPE_NEW_COMPLETE = 'grr.entry_type.new.complete';
    const ENTRY_TYPE_EDIT_SUCCESS = 'grr.entry_type.edit.success';
    const ENTRY_TYPE_DELETE_SUCCESS = 'grr.entry_type.delete.success';

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
