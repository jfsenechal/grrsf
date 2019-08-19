<?php

namespace App\Tests\Front\Controller;

use Symfony\Component\Panther\PantherTestCase;

class DefaultControllerTest extends PantherTestCase
{
    public function testHomePageGrr()
    {
        $url = "/";
        $client = self::createClient();
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testSomething()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello World');
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
