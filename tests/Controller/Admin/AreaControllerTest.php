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

class AreaControllerTest extends BaseTesting
{
    public function testNewArea()
    {
        $this->loadFixtures();

        $this->administrator->request('GET', '/admin/area/');
        self::assertResponseIsSuccessful();
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

    public function testEditEntryTypeAssoc()
    {
        $this->loadFixtures();

        $this->administrator->request('GET', 'admin/area/');
        $this->administrator->clickLink('Hdv');
        self::assertResponseIsSuccessful();

        $crawler = $this->administrator->clickLink('Types d\'entrée');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['assoc_type_for_area[entryTypes]'][0]->tick();
        $form['assoc_type_for_area[entryTypes]'][1]->tick();

        $this->administrator->submit($form);
        $this->administrator->followRedirect();
        self::assertResponseIsSuccessful();

        $this->assertContains(
            'Hdv',
            $this->administrator->getResponse()->getContent()
        );
    }

    protected function loadFixtures()
    {
        $files =
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'user.yaml',
                $this->pathFixtures.'entry_type.yaml',
            ];

        $this->loader->load($files);
    }
}
