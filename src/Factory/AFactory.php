<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 17:42
 */

namespace App\Factory;


use App\Entity\GrrEntry;

class AFactory implements FactoryInterface
{
    /**
     * @return object
     */
    public function createNew()
    {
        $t = new GrrEntry();
        $t->setBeneficiaire('jf');
        return $t;
    }
}