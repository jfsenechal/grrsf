<?php

include_once 'include/connect.inc.php';
include_once 'PdoGrr.php';

$dsn = 'mysql:host='.$dbHost.';port='.$dbPort.';dbname='.$dbDb;
try {
    $pdo = new PdoGrr($dsn, $dbUser, $dbPass, $table_prefix);
    echo json_encode($pdo->getEntries());
} catch (\PDOException $exception) {
    echo json_encode(['error' => $exception->getMessage()]);
}
