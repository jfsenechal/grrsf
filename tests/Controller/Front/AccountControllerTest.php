<?php
/**
 * This file is part of GrrSf application.
 *
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 28/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Controller\Front;

use App\Tests\Repository\BaseRepository;

class AccountControllerTest extends BaseRepository
{
    public function testNew()
    {
        $this->loadFixtures();

        $this->administrator->request('GET', '/account/show');
        $crawler = $this->administrator->clickLink('Modifier');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['user[first_name]']->setValue('Raoul');

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'Raoul',
            $this->administrator->getResponse()->getContent()
        );

        self::assertResponseIsSuccessful();
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
