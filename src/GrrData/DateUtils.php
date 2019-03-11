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
    private static $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public static function getJoursSemaine()
    {
        $data = [];
        $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

        foreach ($days as $day) {
            $data[] = self::$translator->trans($day);
        }

        return $data;
    }

    public static function getHeures()
    {
        return range(0, 23);
    }

    public static function getAffichageFormat()
    {
        return [self::$translator->trans('twentyfourhourFormat12'), self::$translator->trans('twentyfourhourFormat24')];
    }
}