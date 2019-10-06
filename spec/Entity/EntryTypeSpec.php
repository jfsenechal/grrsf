<?php

namespace spec\App\Entity;

use App\Entity\EntryType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EntryTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(EntryType::class);
        $this->getName()->shouldBeString();
    }
}
