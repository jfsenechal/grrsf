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
     * Gestionnaire des user.
     *
     * @return string
     */
    public static function getRoleManagerUser()
    {
        return 'ROLE_GRR_MANAGER_USER';
    }

    /**
     * Administrateur de grr.
     *
     * @return string
     */
    public static function getRoleManagerArea()
    {
        return 'ROLE_GRR_MANAGER_AREA';
    }

    /**
     * Administrateur d'une area
     * Peut modifier, supprimer l'area et ses ressources.
     *
     * @return string
     */
    public static function getRoleAdministratorArea()
    {
        return 'ROLE_GRR_ADMINISTRATOR_AREA';
    }

    /**
     * Administrateur de grr.
     *
     * @return string
     */
    public static function getRoleGrrAdministrator()
    {
        return 'ROLE_GRR_ADMINISTRATOR';
    }

    public static function getRolesForAuthorization() {
        $areaAdministrator = new \stdClass();
        $areaAdministrator->value = 1;
        $areaAdministrator->name = 'authorization.role.area.administrator.label';
        $areaAdministrator->description = 'authorization.role.area.administrator.help';

        $resourceAdministrator = new \stdClass();
        $resourceAdministrator->value = 2;
        $resourceAdministrator->name = 'authorization.role.resource.administrator.label';
        $resourceAdministrator->description = 'authorization.role.resource.administrator.help';

        return [$areaAdministrator, $resourceAdministrator];
    }
}
