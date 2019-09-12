<?php
/**
 * This file is part of GrrSf application.
 *
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 28/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Controller\Admin;

use App\Tests\BaseTesting;

class RoomControllerTest extends BaseTesting
{
    public function testNewRoom()
    {
        $this->loadFixtures();

        $this->administrator->request('GET', '/admin/area/');
        $this->administrator->clickLink('Hdv');
        $crawler = $this->administrator->clickLink('Nouvelle ressource');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['room[name]']->setValue('Room demo');

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'Room demo',
            $this->administrator->getResponse()->getContent()
        );

        $this->administrator->request('GET', '/admin/area/');
        $this->administrator->clickLink('Hdv');
        $this->administrator->clickLink('Room demo');
        self::assertResponseIsSuccessful();
    }

    public function testEdit()
    {
        $this->loadFixtures();

        $this->administrator->request('GET', 'admin/area/');
        $this->administrator->clickLink('Hdv');
        $this->administrator->clickLink('Salle Conseil');
        self::assertResponseIsSuccessful();

        $crawler = $this->administrator->clickLink('Modifier');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['room[name]']->setValue('Salle Conseil demo');

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'Salle Conseil demo',
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
