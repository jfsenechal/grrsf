<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 11/03/19
 * Time: 21:44
 */

namespace App\GrrData;


use Symfony\Contracts\Translation\TranslatorInterface;

class TypeAreaData
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function disponibleFor()
    {
        return [
            2 => $this->translator->trans('Tous'),
            3 => $this->translator->trans('Les gestionnaires et administrateurs'),
            5 => $this->translator->trans('Seulement les administrateurs'),
        ];
    }

    public function typeLettres()
    {
        return [
            'A' => 'A',
            'B' => 'B',
            'C' => 'C',
            'D' => 'D',
            'E' => 'E',
            'F' => 'F',
            'G' => 'G',
            'H' => 'H',
            'I' => 'I',
            'J' => 'J',
            'K' => 'K',
            'L' => 'L',
            'M' => 'M',
            'N' => 'N',
            'O' => 'O',
            'P' => 'P',
            'Q' => 'Q',
            'R' => 'R',
            'S' => 'S',
            'T' => 'T',
            'U' => 'U',
            'V' => 'V',
            'W' => 'W',
            'X' => 'X',
            'Y' => 'Y',
            'Z' => 'Z',
            'AA' => 'AA',
            'AB' => 'AB',
            'AC' => 'AC',
            'AD' => 'AD',
            'AE' => 'AE',
            'AF' => 'AF',
            'AG' => 'AG',
            'AH' => 'AH',
            'AI' => 'AI',
            'AJ' => 'AJ',
            'AK' => 'AK',
            'AL' => 'AL',
            'AM' => 'AM',
            'AN' => 'AN',
            'AO' => 'AO',
            'AP' => 'AP',
            'AQ' => 'AQ',
            'AR' => 'AR',
            'AS' => 'AS',
            'AT' => 'AT',
            'AU' => 'AU',
            'AV' => 'AV',
            'AW' => 'AW',
            'AX' => 'AX',
            'AY' => 'AY',
            'AZ' => 'AZ',
            'BA' => 'BA',
            'BB' => 'BB',
            'BC' => 'BC',
            'BD' => 'BD',
            'BE' => 'BE',
            'BF' => 'BF',
            'BG' => 'BG',
            'BH' => 'BH',
            'BI' => 'BI',
            'BJ' => 'BJ',
            'BK' => 'BK',
            'BL' => 'BL',
            'BM' => 'BM',
            'BN' => 'BN',
            'BO' => 'BO',
            'BP' => 'BP',
            'BQ' => 'BQ',
            'BR' => 'BR',
            'BS' => 'BS',
            'BT' => 'BT',
            'BU' => 'BU',
            'BV' => 'BV',
            'BW' => 'BW',
            'BX' => 'BX',
            'BY' => 'BY',
            'BZ' => 'BZ',
            'CA' => 'CA',
            'CB' => 'CB',
            'CC' => 'CC',
            'CD' => 'CD',
            'CE' => 'CE',
            'CF' => 'CF',
            'CG' => 'CG',
            'CH' => 'CH',
            'CI' => 'CI',
            'CJ' => 'CJ',
            'CK' => 'CK',
            'CL' => 'CL',
            'CM' => 'CM',
            'CN' => 'CN',
            'CO' => 'CO',
            'CP' => 'CP',
            'CQ' => 'CQ',
            'CR' => 'CR',
            'CS' => 'CS',
            'CT' => 'CT',
            'CU' => 'CU',
            'CV' => 'CV',
        ];
    }
}