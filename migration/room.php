<?php
include_once 'include/connect.inc.php';
include_once 'PdoGrr.php';

$dsn = 'mysql:host='.$dbHost.';port='.$dbPort.';dbname='.$dbDb;
$pdo = new PdoGrr($dsn, $dbUser, $dbPass, $table_prefix);

echo json_encode($pdo->getRooms());