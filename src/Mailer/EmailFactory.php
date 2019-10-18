<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 18/10/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Mailer;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;

class EmailFactory
{
    private function __construct()
    {
    }

    public static function createNew()
    {
        return new Email();
    }

    public static function createNewTemplated()
    {
        return new TemplatedEmail();
    }
}