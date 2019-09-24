<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 23/09/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Tests\Controller\Front;


use App\Periodicity\PeriodicityConstant;
use App\Router\FrontRouterHelper;
use App\Tests\BaseTesting;
use Carbon\CarbonInterface;
use Symfony\Component\Routing\Router;

class EntryPeriodicityEditControllerTest extends BaseTesting
{
    public function testEditOneEntryPeriodicity()
    {
        $this->loadFixtures();

        $entry = $this->getEntry('Tous les jours pendant 3 jours');

        $this->administrator->request('GET', '/front/entry/'.$entry->getId());
        self::assertResponseIsSuccessful();

        $crawler = $this->administrator->clickLink('Modifier la réservation');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['entry[name]']->setValue('Tous les jours pendant 4 jours');

        $this->administrator->submit($form);
        $crawler = $this->administrator->followRedirect();

        $this->assertContains(
            'Tous les jours pendant 4 jours',
            $this->administrator->getResponse()->getContent()
        );

        $area = $entry->getRoom()->getArea();
        $start = $entry->getStartTime();
        $year = $start->format('Y');
        $month = $start->format('m');
        $url = '/front/monthview/area/'.$area->getId().'/year/'.$year.'/month/'.$month.'/room';

        $crawler = $this->administrator->request('GET', $url);

        self::assertCount(
            3,
            $crawler->filter('a:contains("Tous les jours pendant 3 jours")')
        );

        self::assertCount(
            1,
            $crawler->filter('a:contains("Tous les jours pendant 4 jours")')
        );
    }

    public function testPeriodicityNone()
    {
        $this->loadFixtures();

        $entry = $this->getEntry('Tous les jours pendant 3 jours');

        $this->administrator->request('GET', '/front/entry/'.$entry->getId());
        self::assertResponseIsSuccessful();

        $crawler = $this->administrator->clickLink('Modifier la périodicité');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['periodicity[periodicity][type]']->setValue(0);

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'Tous les jours pendant 3 jours',
            $this->administrator->getResponse()->getContent()
        );

        $area = $entry->getRoom()->getArea();
        $start = $entry->getStartTime();
        $year = $start->format('Y');
        $month = $start->format('m');
        $url = '/front/monthview/area/'.$area->getId().'/year/'.$year.'/month/'.$month.'/room';

        $crawler = $this->administrator->request('GET', $url);

        self::assertCount(
            1,
            $crawler->filter('a:contains("Tous les jours pendant 3 jours")')
        );
    }

    public function testEditChangeEndDatePeriodicity()
    {
        $this->loadFixtures();

        $entry = $this->getEntry('Tous les jours pendant 3 jours');

        $this->administrator->request('GET', '/front/entry/'.$entry->getId());
        self::assertResponseIsSuccessful();

        $crawler = $this->administrator->clickLink('Modifier la périodicité');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['periodicity[periodicity][endTime][day]']->setValue(8);

        $this->administrator->submit($form);
        $this->administrator->followRedirect();

        $this->assertContains(
            'Tous les jours pendant 3 jours',
            $this->administrator->getResponse()->getContent()
        );

        $area = $entry->getRoom()->getArea();
        $start = $entry->getStartTime();
        $year = $start->format('Y');
        $month = $start->format('m');
        $url = '/front/monthview/area/'.$area->getId().'/year/'.$year.'/month/'.$month.'/room';

        $crawler = $this->administrator->request('GET', $url);

        self::assertCount(
            7,
            $crawler->filter('a:contains("Tous les jours pendant 3 jours")')
        );
    }

    public function testEditChangeTypePeriodicity()
    {
        $this->loadFixtures();

        $entry = $this->getEntry('Tous les jours pendant 3 jours');

        $this->administrator->request('GET', '/front/entry/'.$entry->getId());
        self::assertResponseIsSuccessful();

        $crawler = $this->administrator->clickLink('Modifier la périodicité');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['periodicity[periodicity][type]']->setValue(PeriodicityConstant::EVERY_MONTH_SAME_DAY);
        $form['periodicity[periodicity][endTime][month]']->setValue(5);
        $form['periodicity[periodicity][endTime][year]']->setValue(2020);

        $this->administrator->submit($form);
        $crawler = $this->administrator->followRedirect();

        $this->assertContains(
            'Tous les jours pendant 3 jours',
            $this->administrator->getResponse()->getContent()
        );

        self::assertCount(
            5,
            $crawler->filter('span:contains("2020 10:00")')
        );
    }

    public function testEditChangeTypeWeekPeriodicity()
    {
        $this->loadFixtures();

        $entry = $this->getEntry('Tous les jours pendant 3 jours');

        $this->administrator->request('GET', '/front/entry/'.$entry->getId());
        self::assertResponseIsSuccessful();

        $crawler = $this->administrator->clickLink('Modifier la périodicité');

        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['periodicity[periodicity][type]']->setValue(PeriodicityConstant::EVERY_WEEK);
        $form['periodicity[periodicity][weekDays][0]']->setValue(CarbonInterface::MONDAY);
        $form['periodicity[periodicity][weekDays][1]']->setValue(CarbonInterface::TUESDAY);
        $form['periodicity[periodicity][weekRepeat]']->setValue(1);
        $form['periodicity[periodicity][endTime][month]']->setValue(1);
        $form['periodicity[periodicity][endTime][year]']->setValue(2020);

        $this->administrator->submit($form);
        $crawler = $this->administrator->followRedirect();

        $this->assertContains(
            'Tous les jours pendant 3 jours',
            $this->administrator->getResponse()->getContent()
        );

        self::assertCount(
            10,
            $crawler->filter('span:contains("12-2019 10:00")')
        );
    }

    /**
     * va pas
     * @return FrontRouterHelper
     */
    protected function initFrontRouterHelper()
    {
        $router = $this->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->setMethods(['generate', 'supports', 'exists'])
            ->getMockForAbstractClass();;

        return new FrontRouterHelper($router);
    }

    protected function loadFixtures()
    {
        $files =
            [
                $this->pathFixtures.'area.yaml',
                $this->pathFixtures.'room.yaml',
                $this->pathFixtures.'entry_type.yaml',
                $this->pathFixtures.'periodicity.yaml',
                $this->pathFixtures.'entry_with_periodicity.yaml',
                $this->pathFixtures.'user.yaml',
            ];

        $this->loader->load($files);
    }
}