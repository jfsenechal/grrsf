<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 24/09/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Router;

use App\Entity\Entry;
use Carbon\Carbon;
use Symfony\Component\Routing\RouterInterface;

class FrontRouterHelper
{
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function generateMonthView(Entry $entry, bool $withRoom = false): string
    {
        $room = $entry->getRoom();
        $area = $room->getArea();

        $date = Carbon::instance($entry->getStartTime());
        $year = $date->year;
        $month = $date->month;

        $params = ['area' => $area->getId(), 'year' => $year, 'month' => $month];

        if ($withRoom) {
            $params['room'] = $room->getId();
        }

        return $this->router->generate('grr_front_month', $params);
    }

}