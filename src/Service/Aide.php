<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 6/03/19
 * Time: 21:43
 */

namespace App\Service;

use Webmozart\Assert\Assert;

/**
 * [scope] function methodName(type name, ...): void|[return-type]
 *{
 *[pre-conditions checks]
 *
 *[failure scenarios]
 *
 *[happy path]
 *
 *[post-condition checks]
 *
 *[return void|specific-return-type]
 *}
 *
 *
 */
class Aide
{
    public function id(int $id)
    {
        Assert::greaterThan($id, 0, 'ID should be greater than 0');
    }
}