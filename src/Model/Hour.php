<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 28/03/19
 * Time: 14:49
 */

namespace App\Model;


class Hour
{
    /**
     * @var \DateTimeInterface
     */
    protected $begin;
    /**
     * @var \DateTimeInterface
     */
    protected $end;

    /**
     * @return \DateTimeInterface
     */
    public function getBegin(): \DateTimeInterface
    {
        return $this->begin;
    }

    /**
     * @param \DateTimeInterface $begin
     */
    public function setBegin(\DateTimeInterface $begin): void
    {
        $this->begin = $begin;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getEnd(): \DateTimeInterface
    {
        return $this->end;
    }

    /**
     * @param \DateTimeInterface $end
     */
    public function setEnd(\DateTimeInterface $end): void
    {
        $this->end = $end;
    }



}