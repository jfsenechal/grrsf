<?php

namespace App\Tests\Controller\Front;

use App\Tests\Repository\BaseRepository;

class DefaultControllerTest extends BaseRepository
{
    public function testHomeFront()
    {
        $this->loadFixtures();

        $url = "/";
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
        $today = new \DateTime('now');
        $area = $this->getArea('Esquare');
        //http://sallessf.local/front/monthview/area/1/year/2019/month/8/room
        $url = '/front/monthview/area/'.$area->getId().'/year/'.$today->format('Y').'/month/'.$today->format(
                'm'
            ).'/room';
        $client = self::createClient();
        $crawler = $client->request('GET', $url);
        self::assertResponseIsSuccessful();
        self::assertCount(1, $crawler->filter('td:contains("Réunion a ce jour")'));

        $crawler = $client->clickLink($today->format('d'))->last();

        //   var_dump($client->getResponse()->getContent());
    }

    public function testWeekView()
    {
        $today = new \DateTime('now');
        $area = $this->getArea('Esquare');
        //http://sallessf.local/front/monthview/area/1/year/2019/month/8/room
        $url = '/front/weekview/area/'.$area->getId().'/year/'.$today->format('Y').'/month/'.$today->format(
                'm'
            ).'/week/'.$today->format('W').'/room';
        $client = self::createClient();
        $crawler = $client->request('GET', $url);
        self::assertResponseIsSuccessful();
        self::assertCount(1, $crawler->filter('td:contains("Réunion a ce jour")'));

        $crawler = $client->clickLink($today->format('d'))->last();

        //   var_dump($client->getResponse()->getContent());
    }

    public function testDayView()
    {
        $today = new \DateTime('now');
        $area = $this->getArea('Esquare');
        //http://sallessf.local/front/monthview/area/1/year/2019/month/8/room
        $url = '/front/dayview/area/'.$area->getId().'/year/'.$today->format('Y').'/month/'.$today->format(
                'm'
            ).'/day/'.$today->format('d').'/room';
        $client = self::createClient();
        $crawler = $client->request('GET', $url);
        self::assertResponseIsSuccessful();
        self::assertCount(1, $crawler->filter('td:contains("Réunion a ce jour")'));

        $crawler = $client->clickLink($today->format('d'))->last();

        //   var_dump($client->getResponse()->getContent());
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
