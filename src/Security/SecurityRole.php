<?php

namespace App\Security;

class SecurityRole
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
     * Role minimal pour être authentifié.
     *
     * @return string
     */
    public static function getRoleGrr()
    {
        return 'ROLE_GRR';
    }

    /**
     * Gestionnaire des utilisateurs
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
     * Administrateur d'une area
     * Peut modifier, supprimer l'area et ses ressources
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
