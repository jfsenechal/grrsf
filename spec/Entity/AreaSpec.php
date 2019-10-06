<?php

namespace spec\App\Entity;

use App\Entity\Area;
use App\Entity\Room;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AreaSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Area::class);
    }

    function it_should_save_a_title()
    {
        $this->setName('Un titre au hasard');
        $this->getName()->shouldReturn('Un titre au hasard');
    }


}
