<?php

namespace App\Tests\Controller\Front;

use App\Model\DurationModel;
use App\Periodicity\PeriodicityConstant;
use App\Tests\Repository\BaseRepository;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class EntryPeriodicityWeeksControllerTest extends BaseRepository
{
    public function testNoDaysSelected()
    {
        $this->loadFixtures();

        $today = new \DateTime();
        $esquare = $this->getArea('Hdv');
        $room = $this->getRoom('Salle Conseil');

        $url = '/front/entry/new/area/'.$esquare->getId().'/room/'.$room->getId().'/year/'.$today->format(
                'Y'
            ).'/month/'.$today->format('m').'/day/'.$today->format('d').'/hour/11/minute/30';

        $crawler = $this->administrator->request('GET', $url);
        self::assertResponseIsSuccessful();

        $end = clone $today;
        $end->modify('+5 days');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['entry[name]']->setValue('My reservation repeated');
        $form['entry[duration][unit]']->setValue(DurationModel::UNIT_TIME_HOURS);
        $form['entry[duration][time]']->setValue(2);
        $form['entry[periodicity][type]']->setValue(PeriodicityConstant::EVERY_WEEK);
        $form['entry[periodicity][endTime][day]']->setValue($end->format('j'));
        $form['entry[periodicity][endTime][month]']->setValue($end->format('n'));
        $form['entry[periodicity][endTime][year]']->setValue($end->format('Y'));

        $this->administrator->submit($form);
        //  var_dump($this->administrator->getResponse()->getContent());

        $this->assertContains(
            'Aucun jours de la semaine sélectionnés',
            $this->administrator->getResponse()->getContent()
        );
    }

    public function testNoRepeatWeek()
    {
        $this->loadFixtures();

        $today = new \DateTime();
        $esquare = $this->getArea('Hdv');
        $room = $this->getRoom('Salle Conseil');

        $url = '/front/entry/new/area/'.$esquare->getId().'/room/'.$room->getId().'/year/'.$today->format(
                'Y'
            ).'/month/'.$today->format('m').'/day/'.$today->format('d').'/hour/11/minute/30';

        $crawler = $this->administrator->request('GET', $url);
        self::assertResponseIsSuccessful();

        $end = clone $today;
        $end->modify('+5 days');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['entry[name]']->setValue('My reservation repeated');
        $form['entry[duration][unit]']->setValue(DurationModel::UNIT_TIME_HOURS);
        $form['entry[duration][time]']->setValue(2);
        $form['entry[periodicity][type]']->setValue(PeriodicityConstant::EVERY_WEEK);
        $form['entry[periodicity][endTime][day]']->setValue($end->format('j'));
        $form['entry[periodicity][endTime][month]']->setValue($end->format('n'));
        $form['entry[periodicity][endTime][year]']->setValue($end->format('Y'));

        $this->administrator->submit($form);

        $this->assertContains(
            'Aucune répétition choisie',
            $this->administrator->getResponse()->getContent()
        );
    }

    public function testEveryWeek()
    {
        $this->loadFixtures();

        $today = new \DateTime();
        $esquare = $this->getArea('Hdv');
        $room = $this->getRoom('Salle Conseil');

        $url = '/front/entry/new/area/'.$esquare->getId().'/room/'.$room->getId().'/year/'.$today->format(
                'Y'
            ).'/month/'.$today->format('m').'/day/'.$today->format('d').'/hour/11/minute/30';

        $crawler = $this->administrator->request('GET', $url);
        self::assertResponseIsSuccessful();

        $end = clone $today;
        $end->modify('+5 days');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['entry[name]']->setValue('My reservation repeated');
        $form['entry[duration][unit]']->setValue(DurationModel::UNIT_TIME_HOURS);
        $form['entry[duration][time]']->setValue(2);
        $form['entry[periodicity][type]']->setValue(PeriodicityConstant::EVERY_WEEK);
        $form['entry[periodicity][endTime][day]']->setValue($end->format('j'));
        $form['entry[periodicity][endTime][month]']->setValue($end->format('n'));
        $form['entry[periodicity][endTime][year]']->setValue($end->format('Y'));
        $form['entry[periodicity][weekDays][0]']->setValue(CarbonInterface::MONDAY);
        $form['entry[periodicity][weekDays][1]']->setValue(CarbonInterface::TUESDAY);
        $form['entry[periodicity][weekRepeat]']->setValue(1);

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'Chaque semaine',
            $this->administrator->getResponse()->getContent()
        );
    }

    public function testEvery2Week()
    {
        $this->loadFixtures();

        $today = new \DateTime();
        $esquare = $this->getArea('Hdv');
        $room = $this->getRoom('Salle Conseil');

        $url = '/front/entry/new/area/'.$esquare->getId().'/room/'.$room->getId().'/year/'.$today->format(
                'Y'
            ).'/month/'.$today->format('m').'/day/'.$today->format('d').'/hour/11/minute/30';

        $crawler = $this->administrator->request('GET', $url);
        self::assertResponseIsSuccessful();

        $end = clone $today;
        $end->modify('+5 days');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['entry[name]']->setValue('My reservation repeated');
        $form['entry[duration][unit]']->setValue(DurationModel::UNIT_TIME_HOURS);
        $form['entry[duration][time]']->setValue(2);
        $form['entry[periodicity][type]']->setValue(PeriodicityConstant::EVERY_WEEK);
        $form['entry[periodicity][endTime][day]']->setValue($end->format('j'));
        $form['entry[periodicity][endTime][month]']->setValue($end->format('n'));
        $form['entry[periodicity][endTime][year]']->setValue($end->format('Y'));
        $form['entry[periodicity][weekDays][0]']->setValue(CarbonInterface::MONDAY);
        $form['entry[periodicity][weekDays][1]']->setValue(CarbonInterface::TUESDAY);
        $form['entry[periodicity][weekRepeat]']->setValue(2);

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        //var_dump($this->administrator->getResponse()->getContent());

        $this->assertContains(
            'Chaque semaine',
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
