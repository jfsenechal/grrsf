<?php

namespace App\Tests\Front\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testHomePageGrr()
    {
        $url = "/";
        $client = self::createClient();
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    /**
     * @dataProvider provideUrls
     *
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);
        //var_dump($client->getResponse()->getContent());
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function provideUrls()
    {
        return [
            ['/'],
            ['/front/monthview/area/3/year/2019/month/8/room'],
            ['/admin'],
        ];
    }*/
}
