<?php

namespace App\Tests\Controller\Front;

use App\Model\DurationModel;
use App\Periodicity\PeriodicityConstant;
use App\Tests\BaseTesting;

class EntryPeriodicityControllerTest extends BaseTesting
{
    public function testBadEndTime()
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

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['entry_with_periodicity[name]']->setValue('My reservation repeated');
        $form['entry_with_periodicity[duration][unit]']->setValue(DurationModel::UNIT_TIME_HOURS);
        $form['entry_with_periodicity[duration][time]']->setValue(2.5);
        $form['entry_with_periodicity[periodicity][type]']->setValue(PeriodicityConstant::EVERY_DAY);
        $form['entry_with_periodicity[periodicity][endTime][day]']->setValue($end->format('j'));
        $form['entry_with_periodicity[periodicity][endTime][month]']->setValue($end->format('n'));
        $form['entry_with_periodicity[periodicity][endTime][year]']->setValue($end->format('Y'));

        $this->administrator->submit($form);

        $this->assertContains(
            'La date de fin de la périodicité doit être plus grande que la date de fin de la réservation',
            $this->administrator->getResponse()->getContent()
        );
    }

    public function testBadDay()
    {
        $this->loadFixtures();

        $today = new \DateTime();
        $esquare = $this->getArea('Hdv');
        $room = $this->getRoom('Salle Conseil');

        $url = '/front/entry/new/area/'.$esquare->getId().'/room/'.$room->getId().'/year/'.$today->format(
                'Y'
            ).'/month/'.$today->format('m').'/day/'.$today->format('d').'/hour/9/minute/30';

        $crawler = $this->administrator->request('GET', $url);
        self::assertResponseIsSuccessful();

        $today->modify('+5 days');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['entry_with_periodicity[name]']->setValue('My reservation repeated');
        $form['entry_with_periodicity[duration][unit]']->setValue(DurationModel::UNIT_TIME_DAYS);
        $form['entry_with_periodicity[duration][time]']->setValue(3);
        $form['entry_with_periodicity[periodicity][type]']->setValue(PeriodicityConstant::EVERY_DAY);
        $form['entry_with_periodicity[periodicity][endTime][day]']->setValue($today->format('j'));
        $form['entry_with_periodicity[periodicity][endTime][month]']->setValue($today->format('n'));
        $form['entry_with_periodicity[periodicity][endTime][year]']->setValue($today->format('Y'));

        $this->administrator->submit($form);

        $this->assertContains(
            'La durée ne peut excéder une journée pour une répétition par jour',
            $this->administrator->getResponse()->getContent()
        );
    }

    public function testDay()
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
        $end->modify('+3 days');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['entry_with_periodicity[name]']->setValue('My reservation repeated');
        $form['entry_with_periodicity[duration][unit]']->setValue(DurationModel::UNIT_TIME_HOURS);
        $form['entry_with_periodicity[duration][time]']->setValue(2.5);
        $form['entry_with_periodicity[periodicity][type]']->setValue(PeriodicityConstant::EVERY_DAY);
        $form['entry_with_periodicity[periodicity][endTime][day]']->setValue($end->format('j'));
        $form['entry_with_periodicity[periodicity][endTime][month]']->setValue($end->format('n'));
        $form['entry_with_periodicity[periodicity][endTime][year]']->setValue($end->format('Y'));

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'My reservation repeated',
            $this->administrator->getResponse()->getContent()
        );

        $this->assertContains(
            '11:30',
            $this->administrator->getResponse()->getContent()
        );

        $this->assertContains(
            '14:00',
            $this->administrator->getResponse()->getContent()
        );

        $format = 'd-m-Y';
        $result = [
            $today->format($format).' 11:30',
            $today->modify('+ 1 day')->format($format).' 11:30',
            $today->modify('+ 1 day')->format($format).' 11:30',
            $today->modify('+ 1 day')->format($format).' 11:30',
        ];

        foreach ($result as $value) {
            $this->assertContains(
                $value,
                $this->administrator->getResponse()->getContent()
            );
        }

        $this->assertContains(
            $today->format($end->format('d-m-Y')),
            $this->administrator->getResponse()->getContent()
        );
    }

    public function testMonthBadEndTime()
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
        $end->modify('+3 days');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['entry_with_periodicity[name]']->setValue('My reservation repeated');
        $form['entry_with_periodicity[duration][unit]']->setValue(DurationModel::UNIT_TIME_HOURS);
        $form['entry_with_periodicity[duration][time]']->setValue(2.5);
        $form['entry_with_periodicity[periodicity][type]']->setValue(PeriodicityConstant::EVERY_MONTH_SAME_DAY);
        $form['entry_with_periodicity[periodicity][endTime][day]']->setValue($end->format('j'));
        $form['entry_with_periodicity[periodicity][endTime][month]']->setValue($end->format('n'));
        $form['entry_with_periodicity[periodicity][endTime][year]']->setValue($end->format('Y'));

        $this->administrator->submit($form);

        $this->assertContains(
            'La date de fin de la périodicité doit au moins dépasser d&#039;un mois par rapport à la de de fin de la réservation',
            $this->administrator->getResponse()->getContent()
        );
    }

    public function testMonthSameDay()
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
        $end->modify('+3 month');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['entry_with_periodicity[name]']->setValue('My reservation repeated');
        $form['entry_with_periodicity[duration][unit]']->setValue(DurationModel::UNIT_TIME_HOURS);
        $form['entry_with_periodicity[duration][time]']->setValue(2.5);
        $form['entry_with_periodicity[periodicity][type]']->setValue(PeriodicityConstant::EVERY_MONTH_SAME_DAY);
        $form['entry_with_periodicity[periodicity][endTime][day]']->setValue($end->format('j'));
        $form['entry_with_periodicity[periodicity][endTime][month]']->setValue($end->format('n'));
        $form['entry_with_periodicity[periodicity][endTime][year]']->setValue($end->format('Y'));

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'My reservation repeated',
            $this->administrator->getResponse()->getContent()
        );

        $this->assertContains(
            '11:30',
            $this->administrator->getResponse()->getContent()
        );

        $this->assertContains(
            '14:00',
            $this->administrator->getResponse()->getContent()
        );

        $this->assertContains(
            $today->format($end->format('d-m-Y')),
            $this->administrator->getResponse()->getContent()
        );

        $format = 'd-m-Y';
        $result = [
            $today->format($format).' 11:30',
            $today->modify('+ 1 month')->format($format).' 11:30',
            $today->modify('+ 1 month')->format($format).' 11:30',
            $today->modify('+ 1 month')->format($format).' 11:30',
        ];

        foreach ($result as $value) {
            $this->assertContains(
                $value,
                $this->administrator->getResponse()->getContent()
            );
        }
    }

    public function testMonthSameWeekDay()
    {
        $this->loadFixtures();

        $today = \DateTime::createFromFormat('Y-m-d', '2019-09-24');
        $esquare = $this->getArea('Hdv');
        $room = $this->getRoom('Salle Conseil');

        $url = '/front/entry/new/area/'.$esquare->getId().'/room/'.$room->getId().'/year/'.$today->format(
                'Y'
            ).'/month/'.$today->format('m').'/day/'.$today->format('d').'/hour/11/minute/30';

        $crawler = $this->administrator->request('GET', $url);
        self::assertResponseIsSuccessful();

        $end = clone $today;
        $end->modify('+3 month');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['entry_with_periodicity[name]']->setValue('My reservation repeated');
        $form['entry_with_periodicity[duration][unit]']->setValue(DurationModel::UNIT_TIME_HOURS);
        $form['entry_with_periodicity[duration][time]']->setValue(2.5);
        $form['entry_with_periodicity[periodicity][type]']->setValue(PeriodicityConstant::EVERY_MONTH_SAME_WEEK_DAY);
        $form['entry_with_periodicity[periodicity][endTime][day]']->setValue($end->format('j'));
        $form['entry_with_periodicity[periodicity][endTime][month]']->setValue($end->format('n'));
        $form['entry_with_periodicity[periodicity][endTime][year]']->setValue($end->format('Y'));

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'My reservation repeated',
            $this->administrator->getResponse()->getContent()
        );

        $this->assertContains(
            '11:30',
            $this->administrator->getResponse()->getContent()
        );

        $this->assertContains(
            '14:00',
            $this->administrator->getResponse()->getContent()
        );

        $this->assertContains(
            $today->format($end->format('d-m-Y')),
            $this->administrator->getResponse()->getContent()
        );

        $result = ['24-09-2019 11:30', '22-10-2019 11:30', '26-11-2019 11:30', '24-12-2019 11:30'];
        foreach ($result as $value) {
            $this->assertContains(
                $value,
                $this->administrator->getResponse()->getContent()
            );
        }
    }

    public function testSameYearBadEndTime()
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
        $end->modify('+10 months');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['entry_with_periodicity[name]']->setValue('My reservation repeated');
        $form['entry_with_periodicity[duration][unit]']->setValue(DurationModel::UNIT_TIME_HOURS);
        $form['entry_with_periodicity[duration][time]']->setValue(2.5);
        $form['entry_with_periodicity[periodicity][type]']->setValue(PeriodicityConstant::EVERY_YEAR);
        $form['entry_with_periodicity[periodicity][endTime][day]']->setValue($end->format('j'));
        $form['entry_with_periodicity[periodicity][endTime][month]']->setValue($end->format('n'));
        $form['entry_with_periodicity[periodicity][endTime][year]']->setValue($end->format('Y'));

        $this->administrator->submit($form);

        $this->assertContains(
            'La date de fin de la périodicité doit au moins dépasser d&#039;un an par rapport à la de de fin de la réservation',
            $this->administrator->getResponse()->getContent()
        );
    }

    public function testSameYear()
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
        $end->modify('+3 year');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['entry_with_periodicity[name]']->setValue('My reservation repeated');
        $form['entry_with_periodicity[duration][unit]']->setValue(DurationModel::UNIT_TIME_HOURS);
        $form['entry_with_periodicity[duration][time]']->setValue(2.5);
        $form['entry_with_periodicity[periodicity][type]']->setValue(PeriodicityConstant::EVERY_YEAR);
        $form['entry_with_periodicity[periodicity][endTime][day]']->setValue($end->format('j'));
        $form['entry_with_periodicity[periodicity][endTime][month]']->setValue($end->format('n'));
        $form['entry_with_periodicity[periodicity][endTime][year]']->setValue($end->format('Y'));

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'My reservation repeated',
            $this->administrator->getResponse()->getContent()
        );

        $this->assertContains(
            '11:30',
            $this->administrator->getResponse()->getContent()
        );

        $this->assertContains(
            '14:00',
            $this->administrator->getResponse()->getContent()
        );

        $this->assertContains(
            $today->format($end->format('d-m-Y')),
            $this->administrator->getResponse()->getContent()
        );

        $format = 'd-m-Y';
        $result = [
            $today->format($format).' 11:30',
            $today->modify('+ 1 year')->format($format).' 11:30',
            $today->modify('+ 1 year')->format($format).' 11:30',
        ];

        foreach ($result as $value) {
            $this->assertContains(
                $value,
                $this->administrator->getResponse()->getContent()
            );
        }
    }

    protected function loadFixtures()
    {
        $files =
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
                $this->pathFixtures.'entry_type.yaml',
                $this->pathFixtures.'user.yaml',
            ];

        $this->loader->load($files);
    }
}
