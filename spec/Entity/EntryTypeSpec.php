<?php

namespace spec\App\Entity;

use App\Entity\EntryType;
use PhpSpec\ObjectBehavior;

class EntryTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(EntryType::class);
        $this->getName()->shouldBeString();
    }
}
