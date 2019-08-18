<?php

namespace App\Tests\Factory;


use App\Entity\Area;
use App\Factory\RoomFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoomFactoryTest extends WebTestCase
{
    public function testCreateNew()
    {
        $area = new Area();
        $area->setName('Lulu');
        $roomFactory = new RoomFactory();
        $room = $roomFactory::createNew($area);

        $this->assertSame('Lulu', $room->getArea()->getName());
    }
}
