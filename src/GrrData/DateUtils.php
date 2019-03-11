<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/19
 * Time: 21:55
 */

namespace App\GrrData;


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

    public function getJoursSemaine()
    {
        $data = [];
        $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

        foreach ($days as $day) {
            $data[] = $this->translator->trans($day);
        }

        return $data;
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