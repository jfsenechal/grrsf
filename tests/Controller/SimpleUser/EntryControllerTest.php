<?php

namespace App\Tests\Controller\SimpleUser;

use App\Model\DurationModel;
use App\Tests\BaseTesting;
use Carbon\Carbon;
use Symfony\Component\DomCrawler\Link;

class EntryControllerTest extends BaseTesting
{
    public function testNew()
    {
        $this->loadFixtures();

        $today = new \DateTime();
        $esquare = $this->getArea('Esquare');
        $room = $this->getRoom('Box');

        $url = '/front/entry/new/area/'.$esquare->getId().'/room/'.$room->getId().'/year/'.$today->format(
                'Y'
            ).'/month/'.$today->format('m').'/day/'.$today->format('d').'/hour/9/minute/30';

        $crawler = $this->bob->request('GET', $url);
        //  var_dump($this->bob->getResponse()->getContent());
        // self::assertResponseIsSuccessful();

        $form = $crawler->selectButton('Sauvegarder')->form();

        $optionApe = $crawler->filter('#entry_with_periodicity_area option:contains("Hdv")');
        $this->assertEquals(0, count($optionApe));

        $optionApe = $crawler->filter('#entry_with_periodicity_area option:contains("Esquare")');
        $this->assertEquals(1, count($optionApe));
        $ape = $optionApe->attr('value');
        $form['entry_with_periodicity[area]']->select($ape);

        $form['entry_with_periodicity[name]']->setValue('My reservation');

        $this->bob->submit($form);
        $this->bob->followRedirect();

        $this->assertContains(
            'My reservation',
            $this->bob->getResponse()->getContent()
        );
    }

    public function testNewBrenda()
    {
        $this->loadFixtures();

        $today = new \DateTime();
        $esquare = $this->getArea('Hdv');
        $room = $this->getRoom('Salle Conseil');

        $url = '/front/entry/new/area/'.$esquare->getId().'/room/'.$room->getId().'/year/'.$today->format(
                'Y'
            ).'/month/'.$today->format('m').'/day/'.$today->format('d').'/hour/9/minute/30';

        $crawler = $this->brenda->request('GET', $url);
        //var_dump($this->brenda->getResponse()->getContent());
        // self::assertResponseIsSuccessful();

        $form = $crawler->selectButton('Sauvegarder')->form();

        $optionApe = $crawler->filter('#entry_with_periodicity_area option:contains("Esquare")');
        $this->assertEquals(0, count($optionApe));

        $option = $crawler->filter('#entry_with_periodicity_area option:contains("Hdv")');
        $this->assertEquals(1, count($option));

        $ape = $option->attr('value');
        $form['entry_with_periodicity[area]']->select($ape);

        $form['entry_with_periodicity[name]']->setValue('My reservation');

        $this->brenda->submit($form);
        $this->brenda->followRedirect();

        $this->assertContains(
            'My reservation',
            $this->brenda->getResponse()->getContent()
        );
    }

    public function testLink()
    {
        $this->loadFixtures();

        $today = new \DateTime();
        $esquare = $this->getArea('Esquare');
        $room = $this->getRoom('Box');

        $url = '/front/dayview/area/'.$esquare->getId().'/year/2019/month/8/day/20/room';
        //front/dayview/area/15/year/2019/month/8/day/20/room

        $this->bob->request('GET', $url);

        $crawler = $this->bob->clickLink('Réunion cst');
        $optionApe = $crawler->filter('div.card-body > a');
        $links = $optionApe->links();//retour à la liste et modifier
        $this->assertCount(2, $links);
        $this->assertContains('Modifier', $links[1]->getNode()->textContent);


        $this->assertContains('Supprimer', $links[1]);
    }

    protected
    function loadFixtures()
    {
        $files =
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
                $this->pathFixtures.'entry_type.yaml',
                $this->pathFixtures.'user.yaml',
                $this->pathFixtures.'authorization.yaml',
                $this->pathFixtures.'entry.yaml',
            ];

        $this->loader->load($files);
    }
}
