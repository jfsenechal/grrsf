<?php

namespace App\Tests\Controller;

use App\Tests\BaseTesting;

class DefaultControllerTest extends BaseTesting
{
    public function testHomeGrr()
    {
        $this->loadFixtures();

        $url = '/';
        $client = self::createClient();
        $client->request('GET', $url);
        self::assertResponseRedirects();
        $client->followRedirect();
        self::assertResponseIsSuccessful();
    }

    protected function loadFixtures()
    {
        $files =
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
            ];

        $this->loader->load($files);
    }
}
