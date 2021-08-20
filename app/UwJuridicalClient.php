<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UwJuridicalClient extends Model
{
    //
    protected $fillable = [
        'claim_id',
        'claim_date',
        'inn',
        'claim_number',
        'agreement_number',
        'agreement_date',
        'resident',
        'juridical_status',
        'nibbd',
        'client_type',
        'jur_name',
        'live_cadastr',
        'owner_form',
        'goverment',
        'registration_region',
        'registration_district',
        'registration_address',
        'phone',
        'hbranch',
        'oked',
        'katm_sir',
        'okpo',
        'code_juridical_person',
        'summa',
        'client_code',
        'loan_type_id',
        'branch_code',
        'local_code',
        'work_user_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(MWorkUsers::class,'work_user_id')->where('isActive', 'A');
    }

    public function department()
    {
        return $this->belongsTo(Department::class,'local_code', 'local_code');
    }

    public function loanType()
    {
        return $this->belongsTo(UwLoanTypes::class,'loan_type_id', 'id');
    }

    public function region()
    {
        return $this->belongsTo(UnRegions::class,'registration_region', 'code');
    }

    public function district()
    {
        return $this->belongsTo(UnDistricts::class,'registration_district', 'code');
    }

    public function filial()
    {
        return $this->belongsTo(Filials::class,'branch_code', 'filial_code');
    }

    function convertNumberToWord($num = false)
    {
        $num = str_replace(array(',', ' '), '' , trim($num));
        if(! $num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array('', 'бир', 'икки', 'уч', 'тўрт', 'беш', 'олти', 'етти', 'саккиз', 'тўққиз', 'ўн', 'ўнбир',
            'ўникки', 'ўнуч', 'ўнтўрт', 'ўнбеш', 'ўнолти', 'ўнетти', 'ўнсаккиз', 'ўнтўққиз'
        );
        $list2 = array('', 'ўн', 'йигирма', 'ўттиз', 'қирқ', 'эллик', 'олтмиш', 'етмиш', 'саксон', 'тўқсон', 'юз');
        $list3 = array('', 'минг', 'миллион', 'миллиард', 'триллион', 'квадриллион', 'квинтиллион', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' юз' . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ( $tens < 20 ) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        return implode(' ', $words);
    }
}
