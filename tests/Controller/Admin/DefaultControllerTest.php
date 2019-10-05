<?php

namespace App\Tests\Controller\Admin;

use App\Tests\BaseTesting;

class DefaultControllerTest extends BaseTesting
{
    public function testAdminHomePage()
    {
        $url = '/admin';
        $client = self::createClient();
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    /*
     * dataProvider provideUrls
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
