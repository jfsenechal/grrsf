<?php

namespace spec\App\Entity;

use App\Entity\Area;
use App\Entity\Room;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RoomSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Room::class);
    }

    function it_should_save_a_title()
    {
        $this->setName('Un titre au hasard');
        $this->getName()->shouldReturn('Un titre au hasard');
    }

    function it_should_save_a_username_using_a_user(Area $area)
    {
        $area->getName()->willReturn('Nek')->shouldBeCalled();
        $this->setArea($area);
        $this->getArea()->getName()->shouldReturn('Nek');
    }

    function let(Area $area)
    {
        $this->beConstructedWith($area);
        $area->getName()->willReturn('Nek');
    }
}
