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

use App\Tests\Repository\BaseRepository;

class UserControllerTest extends BaseRepository
{
    public function testNew()
    {
        $this->loadFixtures();

        $this->administrator->request('GET', '/admin/user/');
        $crawler = $this->administrator->clickLink('Nouvelle utilisateur');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['user_new[name]']->setValue('Doe');
        $form['user_new[first_name]']->setValue('Raoul');
        $form['user_new[email]']->setValue('raoul@domain.com');
        $form['user_new[password]']->setValue('123456789');

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'raoul@domain.com',
            $this->administrator->getResponse()->getContent()
        );

        $this->administrator->request('GET', '/admin/user/');
        $this->administrator->clickLink('raoul@domain.com');
        self::assertResponseIsSuccessful();
    }

    public function testEdit()
    {
        $this->loadFixtures();

        $this->administrator->request('GET', '/admin/user/');
        $this->administrator->clickLink('alice@domain.be')->first();
        $crawler = $this->administrator->clickLink('Modifier');
        self::assertResponseIsSuccessful();

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['user_admin[name]']->setValue('Doen');

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'Doen',
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
