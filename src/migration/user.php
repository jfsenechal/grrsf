<?php

include_once __DIR__.'/auth.php';

$pdo = new PdoGrr($dbHost, $dbPort, $dbDb, $dbUser, $dbPass, $table_prefix);

echo json_encode($pdo->getUsers());