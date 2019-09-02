<?php
/**
 * This file is part of GrrSf application.
 *
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 28/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Controller\Admin\Controller;

use App\Tests\Repository\BaseRepository;

class EntryTypeControllerTest extends BaseRepository
{
    public function testNewTypeLetterAlreadyUse()
    {
        $this->loadFixtures();

        $this->administrator->request('GET', '/admin/entry/type/');
        $crawler = $this->administrator->clickLink("Nouveau type d'entrée");

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['type_entry[letter]']->setValue('F');
        $form['type_entry[name]']->setValue('My type');

        $this->administrator->submit($form);
        //  $this->administrator->followRedirect();

        $this->assertContains(
            'Cette lettre est déjà utilisée',
            $this->administrator->getResponse()->getContent()
        );
    }

    public function testNewTypeLetter()
    {
        $this->loadFixtures();

        $this->administrator->request('GET', '/admin/entry/type/');
        $crawler = $this->administrator->clickLink("Nouveau type d'entrée");

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['type_entry[letter]']->setValue('S');
        $form['type_entry[name]']->setValue('My type');

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'My type',
            $this->administrator->getResponse()->getContent()
        );
    }

    public function testEditType()
    {
        $this->loadFixtures();

        $this->administrator->request('GET', '/admin/entry/type/');
        $this->administrator->clickLink('Bureau');
        self::assertResponseIsSuccessful();

        $crawler = $this->administrator->clickLink('Modifier');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['type_entry[name]']->setValue('Bureaux');

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'Bureaux',
            $this->administrator->getResponse()->getContent()
        );
    }

    protected function loadFixtures()
    {
        $files =
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
                $this->pathFixtures.'entry_type.yaml',
                $this->pathFixtures.'users.yaml',
            ];

        $this->loader->load($files);
    }
}
