<?php

namespace App\Tests\Controller\Admin;

use App\Tests\Repository\BaseRepository;

class AuthorizationAreaControllerTest extends BaseRepository
{
    public function testAreaAdministrator()
    {
        $this->loadFixtures();

        $user = $this->getUser('jf@marche.be');
        $area = $this->getArea('Esquare');

        $url = '/admin/area/'.$area->getId();
        $this->administrator->request('GET', $url);
        self::assertResponseIsSuccessful();
        $this->administrator->clickLink('Droits');
        self::assertResponseIsSuccessful();
        $crawler = $this->administrator->clickLink('Ajouter');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['authorization_area[role]']->setValue(1);
        $form['authorization_area[users][0]']->tick();
        $form['authorization_area[users][1]']->tick();
        // $form->setValues(['authorization_area[users]' => [$user->getId()]]);

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'Nouvelle autorisation bien ajoutée',
            $this->administrator->getResponse()->getContent()
        );
    }

    public function testRoomManager()
    {
        $this->loadFixtures();

        $user = $this->getUser('jf@marche.be');
        $area = $this->getArea('Esquare');

        $url = '/admin/area/'.$area->getId();
        $this->administrator->request('GET', $url);
        self::assertResponseIsSuccessful();
        $this->administrator->clickLink('Droits');
        self::assertResponseIsSuccessful();
        $crawler = $this->administrator->clickLink('Ajouter');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['authorization_area[role]']->setValue(2);
        $form['authorization_area[users][0]']->tick();
        $form['authorization_area[users][1]']->tick();

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'Nouvelle autorisation bien ajoutée',
            $this->administrator->getResponse()->getContent()
        );
    }

    public function testAlreadyManager()
    {
        $this->loadFixtures();

        $user = $this->getUser('jf@marche.be');
        $area = $this->getArea('Esquare');

        $url = '/admin/area/'.$area->getId();
        $this->administrator->request('GET', $url);
        self::assertResponseIsSuccessful();
        $this->administrator->clickLink('Droits');
        self::assertResponseIsSuccessful();
        $crawler = $this->administrator->clickLink('Ajouter');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['authorization_area[role]']->setValue(2);
        $form['authorization_area[users][0]']->tick();

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'Nouvelle autorisation bien ajoutée',
            $this->administrator->getResponse()->getContent()
        );

        /**
         * second
         */
        $url = '/admin/area/'.$area->getId();
        $this->administrator->request('GET', $url);
        self::assertResponseIsSuccessful();
        $this->administrator->clickLink('Droits');
        self::assertResponseIsSuccessful();
        $crawler = $this->administrator->clickLink('Ajouter');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['authorization_area[role]']->setValue(2);
        $form['authorization_area[users][0]']->tick();

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'L&#039; autorisation pour Esquare existe déjà',
            $this->administrator->getResponse()->getContent()
        );
    }

    /**
     * ne va pas a cause de la validation des rooms
     */
    public function estRooms()
    {
        $this->loadFixtures();

        $user = $this->getUser('jf@marche.be');
        $area = $this->getArea('Esquare');
        $hdv = $this->getArea('Hdv');

        $url = '/admin/user/'.$user->getId();
        $this->administrator->request('GET', $url);
        self::assertResponseIsSuccessful();
        $this->administrator->clickLink('Droits');
        self::assertResponseIsSuccessful();
        $crawler = $this->administrator->clickLink('Ajouter');

        $box = $this->getRoom('Box');
        $relax = $this->getRoom('Relax Room');

        $form = $crawler->selectButton('Sauvegarder')->form();

//        $form['authorization_user[rooms]']->disableValidation()->select('Invalid value');

        $form['authorization_user[area]']->setValue($area->getId());
        $form['authorization_user[rooms]'] = [$box->getId(), $area->getId()];

        $this->administrator->submit($form);

        $this->administrator->followRedirect();

        $this->assertContains(
            'Esquare',
            $this->administrator->getResponse()->getContent()
        );
    }

    protected function loadFixtures()
    {
        $files =
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
                $this->pathFixtures.'users.yaml',
            ];

        $this->loader->load($files);
    }

}
