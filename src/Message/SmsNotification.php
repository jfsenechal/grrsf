<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 14/03/19
 * Time: 11:07
 */

namespace App\Message;


class SmsNotification
{
    private $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }
}