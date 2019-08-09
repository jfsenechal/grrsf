<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 11/03/19
 * Time: 16:48.
 */

namespace App\GrrData;

use Symfony\Contracts\Translation\TranslatorInterface;

class UserData
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function statutsList()
    {
        return [
            'visiteur' => $this->translator->trans('Visiteur'),
            'utilisateur' => $this->translator->trans('Usager'),
            'gestionnaire_utilisateur' => $this->translator->trans('Gestionnaire des utilisateurs'),
            'administrateur' => $this->translator->trans('Administrateur'),
        ];
    }

    public function etatsList()
    {
        return [
            'actif' => $this->translator->trans('Actif'),
            'inactif' => $this->translator->trans('Inactif'),
        ];
    }
}
