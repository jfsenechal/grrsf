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
     * clef de type rep_type_0,rep_type_1,...
     *
     * @return array
     */
    public function getTypesPeriodicite()
    {
        $vocab = [];
        $vocab[0] = $this->translator->trans('periodicity.type.none');
        $vocab[1] = $this->translator->trans('periodicity.type.everyday');
        $vocab[2] = $this->translator->trans('periodicity.type.everyweek');
        $vocab[3] = $this->translator->trans('periodicity.type.everymonth.sameday');
        $vocab[4] = $this->translator->trans('periodicity.type.everyyear');
        $vocab[5] = $this->translator->trans('periodicity.type.everymonth.sameweek');
        $vocab[6] = $this->translator->trans('periodicity.type.cycle.days');

        return $vocab;
    }

    public function getTypePeriodicite(int $type)
    {
        if (isset($this->getTypesPeriodicite()[$type])) {
            return $this->getTypesPeriodicite()[$type].' ('.$type.')';
        }

        return $type;
    }

    public function getNumWeeks(int $value)
    {
        $weeklist = ['unused', 'every week', 'week 1/2', 'week 1/3', 'week 1/4', 'week 1/5'];

        return $weeklist[$value];
    }

    /**
     * field: statut_entry
     * e : Signaler que la ressource est empruntée
     * dans le cadre de cette réservation et envoyer quotidiennement un mail notifiant le retard.emprentee
     * y:Signaler que la ressource est empruntée dans le cadre de cette réservation.
     *
     * @param $value
     */
    public function getStatutEntry($value)
    {
    }

    /**
     * field:entry_type
     * Type de periode : aucune, chaque jour, chaque semaine, chaque mois.
     */
    public function getEntryTypes()
    {
        return [0 => 0, 1 => 1, 2 => 2];
    }

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
