<?php


namespace App\Modules;


interface GrrModuleInterface
{
    public function getSupport(): string;

    public function postContent();

}