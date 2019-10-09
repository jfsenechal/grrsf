<?php

namespace spec\App\Entity;

use App\Entity\Area;
use PhpSpec\ObjectBehavior;

class AreaSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Area::class);
    }

    public function it_should_save_a_title()
    {
        $this->setName('Un titre au hasard');
        $this->getName()->shouldReturn('Un titre au hasard');
    }
}
