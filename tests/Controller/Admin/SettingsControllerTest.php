<?php

namespace App\Tests\Controller\Admin;

use App\Tests\BaseTesting;

class SettingsControllerTest extends BaseTesting
{
    public function testAnonyme()
    {
        $client = static::createClient();
        $client->request('GET', 'admin/setting/');

        self::assertResponseRedirects();
        $client->followRedirect();
        $this->assertResponseIsSuccessful();

        self::assertSelectorTextContains('h1', 'Authentification');
    }

    public function testUserConnected()
    {
        $this->loadFixtures();

        $this->administrator->request('GET', 'admin/setting/');

        self::assertSelectorTextContains('h3', 'Paramètres de Grr');

        $crawler = $this->administrator->clickLink('Editer');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['general_setting[nb_calendar]']->setValue(1);
        $form['general_setting[company]']->setValue('Grr');

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'Grr',
            $this->administrator->getResponse()->getContent()
        );
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
