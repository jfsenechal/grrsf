<?php
/**
 * This file is part of GrrSf application.
 *
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 22/08/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Handler;

use App\Entry\HandlerEntry;
use App\Tests\BaseTesting;

/**
 * todo test  handler
 * Class HandlerEntryTest.
 */
class HandlerEntryTest extends BaseTesting
{
    public function testBidon()
    {
        self::assertTrue(true);
    }

    protected function initHandler(): HandlerEntry
    {
        $handler = new HandlerEntry();

        return $handler;
    }
}
