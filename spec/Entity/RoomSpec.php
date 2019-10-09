<?php

namespace spec\App\Entity;

use App\Entity\Area;
use App\Entity\Room;
use PhpSpec\ObjectBehavior;

class RoomSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Room::class);
    }

    public function it_should_save_a_title()
    {
        $this->setName('Un titre au hasard');
        $this->getName()->shouldReturn('Un titre au hasard');
    }

    public function it_should_save_a_username_using_a_user(Area $area)
    {
        $area->getName()->willReturn('Nek')->shouldBeCalled();
        $this->setArea($area);
        $this->getArea()->getName()->shouldReturn('Nek');
    }

    public function let(Area $area)
    {
        $this->beConstructedWith($area);
        $area->getName()->willReturn('Nek');
    }
}
