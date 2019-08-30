<?php

namespace App\Tests\Controller\Front;

use App\Tests\Repository\BaseRepository;

class EntryControllerTest extends BaseRepository
{
    public function testNew()
    {
        $this->loadFixtures();

        $today = new \DateTime();

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
