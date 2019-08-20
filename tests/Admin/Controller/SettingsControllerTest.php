<?php

namespace App\Tests\Admin\Controller;

use Symfony\Component\Panther\PantherTestCase;

class SettingsControllerTest extends PantherTestCase
{
    public function testSomething()
    {
        $client = static::createClient();
        $client->request('GET', 'admin/setting/');

        self::assertResponseRedirects();
        //$this->assertResponseIsSuccessful();
        $crawler = $client->followRedirect();

        self::assertSelectorTextContains('h1', 'Authentification');
    }
}
