<?php
//phpstan/phpstan-doctrine

require dirname(__DIR__).'/config/bootstrap.php';
use App\Kernel;

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

return $kernel->getContainer()->get('doctrine')->getManager();
