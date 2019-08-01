<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 21:55
 */

namespace App\GrrData;


use Carbon\Carbon;
use Symfony\Contracts\Translation\TranslatorInterface;

class DateUtils
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public static function getJoursSemaine()
    {
        $days = Carbon::getDays();
        //if lundi first
        $days[] = $days[0];
        unset($days[0]);

        return $days;
    }

    public static function getHeures()
    {
        return range(0, 23);
    }

    public function getAffichageFormat()
    {
        return [$this->translator->trans('twentyfourhourFormat12'), $this->translator->trans('twentyfourhourFormat24')];
    }
}