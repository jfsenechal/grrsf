<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 20/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Tests\Factory;


use App\Factory\CarbonFactory;
use App\Factory\DayFactory;
use App\I18n\LocalHelper;
use App\Model\Day;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class DayFactoryTest extends WebTestCase
{
    /**
     * @var DayFactory
     */
    private $dayFactory;

    protected function setUp(): void
    {
        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $requestStack = $this->createMock(RequestStack::class);
        $localHelper = new LocalHelper($parameterBag, $requestStack);
        $carbonFactory = new CarbonFactory($localHelper);
        $this->dayFactory = new DayFactory($carbonFactory);
    }

    public function testCreateImmutable()
    {
        $day = $this->dayFactory->createImmutable(2019, 10, 1);
        $this->assertInstanceOf(Day::class, $day);
        $this->assertInstanceOf(ArrayCollection::class, $day->getEntries());
    }

    public function testCreateFromCarbon()
    {
        $carbon = Carbon::today();
        $day = $this->dayFactory->createFromCarbon($carbon);
        $this->assertInstanceOf(Day::class, $day);
        $this->assertInstanceOf(ArrayCollection::class, $day->getEntries());
    }
}