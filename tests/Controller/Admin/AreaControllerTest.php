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

class AreaControllerTest extends BaseRepository
{
    public function testNewArea()
    {
        $this->loadFixtures();

        $this->administrator->request('GET', '/admin/area/');
        $crawler = $this->administrator->clickLink('Nouveau domaine');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['area[name]']->setValue('Area demo');

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'Area demo',
            $this->administrator->getResponse()->getContent()
        );

        $this->administrator->request('GET', '/admin/area/');
        $this->administrator->clickLink('Area demo');
        self::assertResponseIsSuccessful();
    }

    public function testEdit()
    {
        $this->loadFixtures();

        $this->administrator->request('GET', 'admin/area/');
        $this->administrator->clickLink('Hdv');
        self::assertResponseIsSuccessful();

        $crawler = $this->administrator->clickLink('Modifier');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['area[name]']->setValue('Hdv demo');

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'Hdv demo',
            $this->administrator->getResponse()->getContent()
        );
    }

    protected function loadFixtures()
    {
        $files =
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'user.yaml',
            ];

        $this->loader->load($files);
    }
}
