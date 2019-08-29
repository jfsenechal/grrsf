<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 22/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Tests\Handler;

use App\Handler\HandlerEntry;
use App\Tests\Repository\BaseRepository;

class HandlerEntryTest extends BaseRepository
{
    public function testBidon() {
        self::assertTrue(true);

    }
    protected function initHandler() : HandlerEntry {
        $handler = new HandlerEntry();

        return $handler;
    }

}