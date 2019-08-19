<?php

namespace App\Tests\Admin\Controller;

use Symfony\Component\Panther\PantherTestCase;

class SettingsControllerTest extends PantherTestCase
{
    public function testSomething()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello World');
    }
}
