<?php

namespace App\Tests\Controller\Front;

use App\Tests\Repository\BaseRepository;

class EntryControllerTest extends BaseRepository
{
    public function testNewNotLogin()
    {
        // $this->loadFixtures();

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
        //    $this->loadFixtures();

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

    protected function loadFixtures()
    {
        $files =
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
                $this->pathFixtures.'entry_type.yaml',
            ];

        $this->loader->load($files);
    }

}
