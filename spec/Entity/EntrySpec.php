<?php

namespace spec\App\Entity;

use App\Entity\Entry;
use PhpSpec\ObjectBehavior;

class EntrySpec extends ObjectBehavior
{
    public function it_is_initializable($reader)
    {
        $reader->beADoubleOf('Date');

        $this->shouldHaveType(Entry::class);
    }

    public function it_outputs_converted_text(Writer $writer)
    {
        $writer->writeText('<p>Hi, there</p>')->shouldBeCalled();

        $this->outputHtml('Hi, there', $writer);
    }
}
