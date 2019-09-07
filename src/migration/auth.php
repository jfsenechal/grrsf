<?php

include_once __DIR__.'/../include/connect.inc.php';
include __DIR__.'/../include/config.inc.php';
include __DIR__.'/../include/misc.inc.php';
include __DIR__.'/../include/functions.inc.php';
include __DIR__.'/../include/'.$dbsys.'.inc.php';
include_once __DIR__.'/../include/session.inc.php';
include_once 'PdoGrr.php';

$nbMaxJoursLogConnexion = 10; //Notice: Undefined variable

$user = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null;
$password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null;

$result = grr_opensession($user, $password);

if ($result > 1) {
    echo json_encode(['error' => 'Impossible de se connecter avec l\'utilisateur grr: '.$user]);
    die();
}

if ((authGetUserLevel(getUserName(), -1, 'area') < 4) && (authGetUserLevel(getUserName(), -1, 'user') != 1)) {
    echo json_encode(['error' => 'L\'utilisateur doit être administrateur de Grr: '.$user]);
    die();
}