<?php

namespace App\Tests\Controller\Front;

use App\Tests\BaseTesting;

class DefaultControllerTest extends BaseTesting
{
    private $name_entry = 'Réunion a ce jour';

    public function testHomeFront()
    {
        $this->loadFixtures();

        $url = '/';
        $client = self::createClient();
        $client->request('GET', $url);
        $client->followRedirect();
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('th', 'Aujourd\'hui');
        //self::assertCount(1, $crawler->filter('th:contains("Mercredi")'));
        self::assertSelectorTextContains('tr', 'Mercredi');
        //  self::assertSelectorTextContains('th', 'Mercredi');
    }

    public function testMonthView()
    {
        $this->loadFixtures();

        $today = new \DateTime('now');
        $area = $this->getArea('Esquare');

        $url = '/front/monthview/area/'.$area->getId().'/year/'.$today->format('Y').'/month/'.$today->format(
                'm'
            ).'/room';
        $client = self::createClient();
        $client->request('GET', $url);
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h6', $this->name_entry);
        //self::assertCount(1, $crawler->filter('div:contains("Réunion a ce jour")'));
        $client->clickLink($today->format('j'))->last();
        self::assertResponseIsSuccessful();
    }

    public function testWeekView()
    {
        $this->loadFixtures();

        $today = new \DateTime('now');
        $area = $this->getArea('Esquare');

        $url = '/front/weekview/area/'.$area->getId().'/year/'.$today->format('Y').'/month/'.$today->format(
                'm'
            ).'/week/'.$today->format('W').'/room';
        $client = self::createClient();
        $client->request('GET', $url);
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h6', $this->name_entry);
        $client->clickLink($today->format('j'))->last();
        self::assertResponseIsSuccessful();
    }

    public function testDayView()
    {
        $this->loadFixtures();

        $today = new \DateTime('now');
        $area = $this->getArea('Esquare');

        $url = '/front/dayview/area/'.$area->getId().'/year/'.$today->format('Y').'/month/'.$today->format(
                'm'
            ).'/day/'.$today->format('d').'/room';
        $client = self::createClient();
        $crawler = $client->request('GET', $url);
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('html', $this->name_entry);
        $client->clickLink($this->name_entry);
        self::assertCount(1, $crawler->filter('th:contains("Box")'));
        self::assertSelectorTextContains('html', 'Box');
        //   self::assertCount(1, $crawler->filter('div:contains("Location")'));
        self::assertSelectorTextContains('html', 'Location');
    }

    protected function loadFixtures()
    {
        $files =
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
                $this->pathFixtures.'entry_type.yaml',
                $this->pathFixtures.'entry_today.yaml',
            ];

        $this->loader->load($files);
    }
}
