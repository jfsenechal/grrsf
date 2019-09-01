<?php

namespace App\Tests\Controller\Front;

use App\Model\DurationModel;
use App\Tests\Repository\BaseRepository;

class EntryControllerTest extends BaseRepository
{
    public function testNewNotLogin()
    {
        $this->loadFixtures();

        $today = new \DateTime();
        $esquare = $this->getArea('Esquare');
        $room = $this->getRoom('Box');

        $url = "/front/entry/new/area/".$esquare->getId()."/room/".$room->getId()."/year/".$today->format(
                'Y'
            )."/month/".$today->format('m')."/day/".$today->format('d')."/hour/9/minute/30";

        $client = self::createClient();
        $client->request('GET', $url);
        self::assertResponseStatusCodeSame(302);
        $client->followRedirect();
        self::assertSelectorTextContains('h1', 'Authentification');
    }

    public function testNew()
    {
        $this->loadFixtures();

        $today = new \DateTime();
        $esquare = $this->getArea('Esquare');
        $room = $this->getRoom('Box');

        $url = "/front/entry/new/area/".$esquare->getId()."/room/".$room->getId()."/year/".$today->format(
                'Y'
            )."/month/".$today->format('m')."/day/".$today->format('d')."/hour/9/minute/30";

        $crawler = $this->administrator->request('GET', $url);
        self::assertResponseIsSuccessful();
        //      var_dump($this->administrator->getResponse()->getContent());

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['entry[name]']->setValue('My reservation');

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'My reservation',
            $this->administrator->getResponse()->getContent()
        );

        $this->assertContains(
            '09:30',
            $this->administrator->getResponse()->getContent()
        );

        $this->assertContains(
            '10:00',
            $this->administrator->getResponse()->getContent()
        );
    }

    /**
     * @dataProvider getMinutes
     * @param int $hourBegin
     * @param int $minuteBegin
     * @param float $minutes
     * @param string $hourEnd
     * @throws \Exception
     */
    public function testNewMinutes(int $hourBegin, int $minuteBegin, float $minutes, string $hourEnd)
    {
        $this->loadFixtures();

        $today = new \DateTime();
        $esquare = $this->getArea('Esquare');
        $room = $this->getRoom('Box');

        $url = "/front/entry/new/area/".$esquare->getId()."/room/".$room->getId()."/year/".$today->format(
                'Y'
            )."/month/".$today->format('m')."/day/".$today->format('d')."/hour/".$hourBegin."/minute/".$minuteBegin;

        $crawler = $this->administrator->request('GET', $url);
        self::assertResponseIsSuccessful();

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['entry[name]']->setValue('My reservation');
        $form['entry[duration][time]']->setValue($minutes);
        $form['entry[duration][unit]']->setValue(DurationModel::UNIT_TIME_MINUTES);

        $this->administrator->submit($form);
        $this->administrator->followRedirect();
        var_dump($this->administrator->getResponse()->getContent());
        $this->assertContains(
            'My reservation',
            $this->administrator->getResponse()->getContent()
        );

        $this->assertContains(
            $hourBegin.':'.$minuteBegin,
            $this->administrator->getResponse()->getContent()
        );

        $this->assertContains(
            $hourEnd,
            $this->administrator->getResponse()->getContent()
        );
    }

    public function getMinutes()
    {
        return [
            [
                'hour_begin' => 9,
                'minute_begin' => 30,
                'minutes' => 35,
                'hour_end' => '10:05',
            ],
            [
                'hour_begin' => 12,
                'minute_begin' => 0,
                'minutes' => 95,
                'hour_end' => '13:35',
            ],
        ];
    }

    public function testNewBadMinutes()
    {
        $this->loadFixtures();

        $today = new \DateTime();
        $esquare = $this->getArea('Esquare');
        $room = $this->getRoom('Box');

        $url = "/front/entry/new/area/".$esquare->getId()."/room/".$room->getId()."/year/".$today->format(
                'Y'
            )."/month/".$today->format('m')."/day/".$today->format('d')."/hour/10/minute/10";

        $crawler = $this->administrator->request('GET', $url);
        self::assertResponseIsSuccessful();

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['entry[name]']->setValue('My reservation');
        $form['entry[duration][time]']->setValue(35.5);
        $form['entry[duration][unit]']->setValue(DurationModel::UNIT_TIME_MINUTES);

        $this->administrator->submit($form);

        var_dump($this->administrator->getResponse()->getContent());
        $this->assertContains(
            'Une nombre à virgule n&#039;est autorisé que pour une durée par heure.',
            $this->administrator->getResponse()->getContent()
        );
    }

    /**
     * @dataProvider getHours
     * @param int $hourBegin
     * @param int $minuteBegin
     * @param float $time
     * @param string $hourEnd
     * @throws \Exception
     */
    public function testNewHours(int $hourBegin, int $minuteBegin, float $time, string $hourEnd)
    {
        $this->loadFixtures();

        $today = new \DateTime();
        $esquare = $this->getArea('Esquare');
        $room = $this->getRoom('Box');

        $url = "/front/entry/new/area/".$esquare->getId()."/room/".$room->getId()."/year/".$today->format(
                'Y'
            )."/month/".$today->format('m')."/day/".$today->format('d')."/hour/".$hourBegin."/minute/".$minuteBegin;

        $crawler = $this->administrator->request('GET', $url);
        self::assertResponseIsSuccessful();

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['entry[name]']->setValue('My reservation');
        $form['entry[duration][time]']->setValue($time);
        $form['entry[duration][unit]']->setValue(DurationModel::UNIT_TIME_HOURS);

        $this->administrator->submit($form);
        $this->administrator->followRedirect();
        $this->assertContains(
            'My reservation',
            $this->administrator->getResponse()->getContent()
        );

        $this->assertContains(
            $hourBegin.':'.$minuteBegin,
            $this->administrator->getResponse()->getContent()
        );

        $this->assertContains(
            $hourEnd,
            $this->administrator->getResponse()->getContent()
        );
    }

    public function getHours()
    {
        return [
            [
                'hour_begin' => 9,
                'minute_begin' => 30,
                'time' => 2.5,
                'hour_end' => '12:00',
            ],
            [
                'hour_begin' => 12,
                'minute_begin' => 20,
                'time' => 3,
                'hour_end' => '15:20',
            ],
            [
                'hour_begin' => 14,
                'minute_begin' => 28,
                'time' => 3.8,
                'hour_end' => '18:16',
            ],
        ];
    }

    protected function loadFixtures()
    {
        $files =
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
                $this->pathFixtures.'entry_type.yaml',
                $this->pathFixtures.'users.yaml',

            ];

        $this->loader->load($files);
    }

}
