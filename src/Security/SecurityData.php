<?php

namespace App\Security;

class SecurityData
{
    public static function getRoles()
    {
        $roles = [self::getRoleGrr(), self::getRoleManagerUser(), self::getRoleAdministrator()];

        return array_combine($roles, $roles);
    }

    /**
     * visiteur.
     *
     * @return string
     */
    public static function getRoleGrr()
    {
        return 'ROLE_GRR';
    }

    /**
     * gestionnaire des utilisateurs
     *
     * @return string
     */
    public static function getRoleManagerUser()
    {
        return 'ROLE_GRR_MANAGER_USER';
    }

    /**
     * Administrateur de grr
     *
     * @return string
     */
    public static function getRoleAdministrator()
    {
        return 'ROLE_GRR_ADMIN';
    }
}
