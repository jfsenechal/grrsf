<?php

namespace App\Security;

class SecurityData
{
    public static function getRoles()
    {
        $roles = [
            self::getRoleGrr(),
            self::getRoleManagerUser(),
            self::getRoleManagerArea(),
            self::getRoleAdministratorArea(),
            self::getRoleGrrAdministrator(),
        ];

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
    public static function getRoleManagerArea()
    {
        return 'ROLE_GRR_MANAGER_AREA';
    }

    /**
     * Administrateur de grr
     *
     * @return string
     */
    public static function getRoleAdministratorArea()
    {
        return 'ROLE_GRR_ADMINISTRATOR_AREA';
    }

    /**
     * Administrateur de grr
     *
     * @return string
     */
    public static function getRoleGrrAdministrator()
    {
        return 'ROLE_GRR_ADMINISTRATOR';
    }
}
