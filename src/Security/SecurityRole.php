<?php

namespace App\Security;

class SecurityRole
{
    /**
     * Role minimal pour être authentifié.
     * Simple visiteur
     */
    const ROLE_GRR = 'ROLE_GRR';
    /**
     * Role utilisateur actif
     */
    const ROLE_GRR_ACTIVE_USER = 'ROLE_GRR_ACTIVE_USER';
    /**
     * Gestionnaire des utilisateurs.
     */
    const ROLE_GRR_MANAGER_USER = 'ROLE_GRR_MANAGER_USER';
    /**
     * Administrateur de grr.
     */
    const ROLE_GRR_ADMINISTRATOR = 'ROLE_GRR_ADMINISTRATOR';

    public static function getRoles()
    {
        $roles = [
            self::ROLE_GRR,
            self::ROLE_GRR_ACTIVE_USER,
            self::ROLE_GRR_MANAGER_USER,
            self::ROLE_GRR_ADMINISTRATOR,
        ];

        return array_combine($roles, $roles);
    }

    /**
     * Utilisé pour le formulaire d'authorization
     * @return array
     */
    public static function getRolesForAuthorization()
    {
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
