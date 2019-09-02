<?php

namespace App\Tests\Controller\Front;

use App\Tests\Repository\BaseRepository;

class DefaultControllerTest extends BaseRepository
{
    public function testHomeFront()
    {
        $this->loadFixtures();

        $url = '/';
        $client = self::createClient();
        $client->request('GET', $url);
        $crawler = $client->followRedirect();
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('th', 'Aujourd\'hui');
        self::assertCount(2, $crawler->filter('th:contains("Mercredi")'));
        //  self::assertSelectorTextContains('th', 'Mercredi');
        //var_dump($client->getResponse()->getContent());
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
        $crawler = $client->request('GET', $url);
        self::assertResponseIsSuccessful();
        self::assertCount(1, $crawler->filter('td:contains("Réunion a ce jour")'));
        $client->clickLink($today->format('j'))->last();
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
        $crawler = $client->request('GET', $url);
        self::assertResponseIsSuccessful();
        self::assertCount(1, $crawler->filter('td:contains("Réunion a ce jour")'));

        $client->clickLink($today->format('j'))->last();
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
        self::assertCount(1, $crawler->filter('td:contains("Réunion a ce jour")'));

        $crawler = $client->clickLink('Réunion a ce jour');
        self::assertCount(1, $crawler->filter('td:contains("Box")'));
        self::assertCount(1, $crawler->filter('td:contains("Location")'));
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
