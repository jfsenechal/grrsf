<?php
/**
 * This file is part of GrrSf application.
 *
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 2/10/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Events;

use Symfony\Contracts\EventDispatcher\Event;

class SettingSuccessEvent extends Event
{
    public function __construct()
    {
    }
}
