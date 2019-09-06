<?php

namespace App\Tests\Controller\Admin;

use App\Tests\Repository\BaseRepository;

class SettingsControllerTest extends BaseRepository
{
    public function testAnonyme()
    {
        $client = static::createClient();
        $client->request('GET', 'admin/setting/');

        self::assertResponseRedirects();
        //$this->assertResponseIsSuccessful();
        $crawler = $client->followRedirect();

        self::assertSelectorTextContains('h1', 'Authentification');
    }

    public function testUserConnected()
    {
        $this->loadFixtures();

        $this->administrator->request('GET', 'admin/setting/');

        self::assertSelectorTextContains('h1', 'Paramètres de Grr');
    }

    protected function loadFixtures()
    {
        $files =
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
                $this->pathFixtures.'user.yaml',
            ];

        $this->loader->load($files);
    }
}
