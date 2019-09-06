<?php

require dirname(__DIR__).'/sallessf/config/bootstrap.php';

use Symfony\Component\Security\Core\Authorization\Voter\Voter;

function provideCases()
{
    yield 'anonymous cannot edit' => [
        'edit',
        'Esquare',
        null,
        Voter::ACCESS_DENIED,
    ];

    yield 'non-owner cannot edit' => [
        'edit',
        'Esquare',
        'bob@domaine.be',
        Voter::ACCESS_DENIED,
    ];


}

foreach (provideCases() as $case) {
    var_dump($case);
}