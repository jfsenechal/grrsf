<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 27/02/19
 * Time: 16:59.
 */

namespace App\GrrData;

use Symfony\Contracts\Translation\TranslatorInterface;

class EntryData
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * field:entry_type
     * Type de periode : aucune, chaque jour, chaque semaine, chaque mois.
     */
    public function getEntryTypes()
    {
        return [0 => 0, 1 => 1, 2 => 2];
    }

    /**
     * Encodage de la date de fin de l'entry.
     *
     * @return array
     */
    public function getUnitsTime()
    {
        $units = [];
        $choices = ['unit.minutes', 'unit.hours', 'unit.days', 'unit.weeks'];
        foreach ($choices as $choice) {
            $units[] = $this->translator->trans($choice);
        }

        return $units;
    }
}
