<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 27/02/19
 * Time: 16:59
 */

namespace App\GrrData;


class EntryData
{
    /**
     * clef de type rep_type_0,rep_type_1,...
     * @return array
     */
    public function getTypesPeriodicite()
    {
        $vocab = [];
        $vocab[0] = "Aucune";
        $vocab[1] = "Chaque jour";
        $vocab[2] = "Chaque semaine";
        $vocab[3] = "Chaque mois, la même date";
        $vocab[4] = "Chaque année, même date";
        $vocab[5] = "Chaque mois, même jour de la semaine";
        $vocab[6] = "Jours Cycle";

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
        $weeklist = array("unused", "every week", "week 1/2", "week 1/3", "week 1/4", "week 1/5");

        return $weeklist[$value];
    }

    /**
     * field: statut_entry
     * e : Signaler que la ressource est empruntée
     * dans le cadre de cette réservation et envoyer quotidiennement un mail notifiant le retard.emprentee
     * y:Signaler que la ressource est empruntée dans le cadre de cette réservation.
     * @param $value
     */
    public function getStatutEntry($value)
    {

    }

    /**
     * field:entry_type
     */
    public function getEntryTypes()
    {
        return [0 => 0, 1 => 1, 2 => 2];
    }
}