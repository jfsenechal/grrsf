<?php

namespace App\Security;

class SecurityData
{
    public static function getRoles()
    {
        $roles = [self::getRoleVisiteur(), self::getRoleUsager(), self::getRoleGestionnaire(), self::getRoleAdmin()];

        return array_combine($roles, $roles);
    }

    /**
     * visiteur
     * @return string
     */
    public static function getRoleVisiteur()
    {
        return 'ROLE_GRR_VISITEUR';
    }

    /**
     * utilisateur
     * @return string
     */
    public static function getRoleUsager()
    {
        return 'ROLE_GRR_USAGER';
    }

    /**
     * gestionnaire_utilisateur
     * @return string
     */
    public static function getRoleGestionnaire()
    {
        return 'ROLE_GRR_GESTIONNAIRE';
    }

    /**
     * administrateur
     * @return string
     */
    public static function getRoleAdmin()
    {
        return 'ROLE_GRR_ADMIN';
    }

}