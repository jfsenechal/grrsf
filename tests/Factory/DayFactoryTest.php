<?php
/**
 * This file is part of GrrSf application.
 *
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 20/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Factory;

use App\Factory\CarbonFactory;
use App\Factory\DayFactory;
use App\I18n\LocalHelper;
use App\Model\Day;
use App\Tests\BaseTesting;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class DayFactoryTest extends BaseTesting
{
    /**
     * @var DayFactory
     */
    private $dayFactory;

    protected function setUp(): void
    {
        parent::setUp();
        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $requestStack = $this->createMock(RequestStack::class);
        $security = $this->createMock(Security::class);
        $localHelper = new LocalHelper($parameterBag, $security, $requestStack);
        $carbonFactory = new CarbonFactory($localHelper);
        $this->dayFactory = new DayFactory($carbonFactory, $localHelper);
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
