<?php

namespace App\Tests\Controller;

use App\Tests\Repository\BaseRepository;
use Symfony\Component\Panther\PantherTestCase;

class DefaultControllerTest extends BaseRepository
{
    public function testHomeGrr()
    {
        $this->loadFixtures();

        $url = "/";
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

    /**
     * @dataProvider provideUrls
     *
     * public function testPageIsSuccessful($url)
     * {
     * $client = self::createClient();
     * $client->request('GET', $url);
     * //var_dump($client->getResponse()->getContent());
     * $this->assertTrue($client->getResponse()->isSuccessful());
     * }
     *
     * public function provideUrls()
     * {
     * return [
     * ['/'],
     * ['/front/monthview/area/3/year/2019/month/8/room'],
     * ['/admin'],
     * ];
     * }*/
}
