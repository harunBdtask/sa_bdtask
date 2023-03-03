<?php if (!defined('APPPATH')) exit('No direct script access allowed');
if (!function_exists('is_valid_logged')) {

    function is_valid_logged()
    {
        $CI = db_connect();
        $log_token = session('auth_token');
        $isAdmin = session('isAdmin');
        if ($isAdmin) {
            return true;
        }
        if (!empty($log_token) && session('isLogIn') == true) {
            //Get Auth token 
            $token = $CI->table('user')
                ->select('auth_token')
                ->where('emp_id', session('id'))
                ->get()
                ->getRow();
            if ($token->auth_token == $log_token) {
                return true;
            } else {
                return false;
            }
            //return true;
        } else {
            return false;
        }
    }
}

// Check fiscal year activate
if (!function_exists('isFiscalYearActive')) {
    function isFiscalYearActive()
    {
        $db = db_connect();
        $builder = $db->table('financial_year')->where('status', 1)->get()->getRow();
        return $builder;
    }
}
if (!function_exists('countries')) {
    function countries($key = '')
    {
        $filePath = FCPATH . 'assets/json/countries.json';
        $jsonString = file_get_contents($filePath);
        $jsonString = json_decode($jsonString);
        if (!empty($key)) {
            foreach ($jsonString as $struct) {
                if ($key == $struct->code) {
                    return $struct->name;
                }
            }
        }
        return $jsonString;
    }
}

// get system settings
if (!function_exists('setting_data')) {

    function setting_data()
    {
        $db = db_connect();
        $builder = $db->table('setting')->get()->getRow();
        return $builder;
    }
}

// if (!function_exists('credit_term')) {

//     function credit_term($num)
//     {
//         $credit_term = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
//         $f = ucwords($credit_term->format($num));
//         return $f;
//     }
// }

// get system settings
if (!function_exists('get_setting')) {

    function get_setting($phrase = '')
    {
        $CI = db_connect();
        if ($phrase != '') {
            $row = $CI->table('setting')
                ->select($phrase)
                ->get()
                ->getRowArray();
            if (!empty($row)) {
                return $row[$phrase];
            } else {
                return false;
            }
        } else {
            $row = $CI->table('setting')
                ->select('*')
                ->get()
                ->getRowArray();
            if (!empty($row)) {
                return $row;
            } else {
                return false;
            }
        }
    }
}

// Get table max ID
if (!function_exists('getMAXID')) {

    function getMAXID($table, $column)
    {
        $CI = db_connect();
        if (!empty($table)) {
            $row = $CI->table($table)
                ->selectMax($column)
                ->get()
                ->getRow()->$column;
            if (!empty($row)) {
                return $row + 1;
            } else {
                return 1;
            }
        } else {
            return false;
        }
    }
}

// Get user acitive or not
// if(!function_exists('isUserActive')) {

//     function isUserActive()
//     {
//         $activities = $this->session->getTempdata('isUserActivity');
//         if($activities===true){
//             $this->session->setTempdata('isUserActivity', true, 60);
//             return true;
//         }else{
//             return false;
//         }
//     }
// }

// Get price format with currency
if (!function_exists('getPriceFormat')) {

    function getPriceFormat($price)
    {
        $currency = session('currency');
        $position = session('cposition');
        $price = number_format($price, 2);
        if ($position == 'right') {
            $str = $price . $currency;
        } else if ($position == 'left') {
            $str = $currency . $price;
        } else if ($position == 'right-space') {
            $str = $price . ' ' . $currency;
        } else {
            $str = $currency . ' ' . $price;
        }
        return $str;
    }
}


if (!function_exists('getSegment')) {
    /**
     * Returns segment value for given segment number or false.
     *@param $uri get current uris
     *
     * @param int $number The segment number for which we want to return the value of
     *
     * @return string|false
     */
    function getSegment($uri, int $number)
    {
        if ($uri->getTotalSegments() >= $number && $uri->getSegment($number)) {
            return $uri->getSegment($number);
        } else {
            return false;
        }
    }
}

if (!function_exists('getTNameByVType')) {
    /**
     * Return datbase table name by voucher type.
     * @param voucher type
     * @return table name string
     */
    function getTNameByVType($param = '')
    {
        $CI = db_connect();
        if (!empty($param)) {
            $row = $CI->table('voucher_type_list')
                ->select('table_name')
                ->where('type', $param)
                ->get()
                ->getRow();
            if (!empty($row)) {
                return $row->table_name;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

/*
|----------------------------------------------
| ID genaretor
|----------------------------------------------     
*/
if (!function_exists('randStrGen')) {
    function randStrGen($mode = null, $len = null)
    {
        $result = "";
        if ($mode == 1) :
            $chars = "A0B1CD2EF3GH4IJ5KLM6NOP7QR8ST9UV11WXY2Zab0cde0fgh9ijkl34mno5pqr8stuv7wxyz";
        elseif ($mode == 2) :
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        elseif ($mode == 3) :
            $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        elseif ($mode == 4) :
            $chars = "0123456789";
        endif;

        $charArray = str_split($chars);
        for ($i = 0; $i < $len; $i++) {
            $randItem = array_rand($charArray);
            $result .= "" . $charArray[$randItem];
        }
        return $result;
    }
}
/*
|----------------------------------------------
|         Ends of id genaretor
|----------------------------------------------
*/


/*
|----------------------------------------------
| Get Database Error
|----------------------------------------------     
*/
if (!function_exists('get_db_error')) {
    function get_db_error()
    {
        $db = db_connect();
        $error = $db->error();
        return 'Database Error Code: ' . $error['code'] . ', Message: ' . $error['message'];
    }
}
/*
|----------------------------------------------
| Get Database Last Query
|----------------------------------------------     
*/
if (!function_exists('get_last_query')) {
    function get_last_query()
    {
        $db = db_connect();
        return $db->getLastQuery();
    }
}
/*
|----------------------------------------------
| Get Database Insert ID
|----------------------------------------------     
*/
if (!function_exists('get_insert_id')) {
    function get_insert_id()
    {
        $db = db_connect();
        return $db->insertID();
    }
}
/*
|----------------------------------------------
| Get Database Affected Rows
|----------------------------------------------     
*/
if (!function_exists('get_affected_rows')) {
    function get_affected_rows()
    {
        $db = db_connect();
        return $db->affectedRows();
    }
}

/*
Date dd/mm/yyyy to yyyy-mm-dd conversion
*/

function date_db_format($date)
{
    if ($date == '') {
        return $date;
    }
    return implode("-", array_reverse(explode("/", $date)));
}


function numberToWords($num)
{
    $ones = array(
        0 => "ZERO",
        1 => "ONE",
        2 => "TWO",
        3 => "THREE",
        4 => "FOUR",
        5 => "FIVE",
        6 => "SIX",
        7 => "SEVEN",
        8 => "EIGHT",
        9 => "NINE",
        10 => "TEN",
        11 => "ELEVEN",
        12 => "TWELVE",
        13 => "THIRTEEN",
        14 => "FOURTEEN",
        15 => "FIFTEEN",
        16 => "SIXTEEN",
        17 => "SEVENTEEN",
        18 => "EIGHTEEN",
        19 => "NINETEEN"
    );

    $tens = array(
        0 => "ZERO",
        1 => "TEN",
        2 => "TWENTY",
        3 => "THIRTY",
        4 => "FORTY",
        5 => "FIFTY",
        6 => "SIXTY",
        7 => "SEVENTY",
        8 => "EIGHTY",
        9 => "NINETY"
    );
    $hundreds = array(
        "HUNDRED",
        "THOUSAND",
        "MILLION",
        "BILLION",
        "TRILLION",
        "QUARDRILLION"
    ); /*limit t quadrillion */
    $num = str_replace('-', '', $num);
    $num = number_format($num, 2, ".", ",");
    $num_arr = explode(".", $num);
    $wholenum = $num_arr[0];
    $decnum = $num_arr[1];
    $whole_arr = array_reverse(explode(",", $wholenum));
    krsort($whole_arr, 1);
    $rettxt = "";
    foreach ($whole_arr as $key => $i) {

        while (substr($i, 0, 1) == "0")
            $i = substr($i, 1, 5);
        if ($i < 20) {
            /* echo "getting:".$i; */
            if (substr($i, 1, 1) != "0") $rettxt .= $ones[$i];
        } elseif ($i < 100) {
            if (substr($i, 0, 1) != "0")  $rettxt .= $tens[substr($i, 0, 1)];
            if (substr($i, 1, 1) != "0") $rettxt .= " " . $ones[substr($i, 1, 1)];
        } else {
            if (substr($i, 0, 1) != "0") $rettxt .= $ones[substr($i, 0, 1)] . " " . $hundreds[0];
            if (substr($i, 1, 1) != "0") $rettxt .= " " . $tens[substr($i, 1, 1)];
            if (substr($i, 2, 1) != "0") $rettxt .= " " . $ones[substr($i, 2, 1)];
        }
        if ($key > 0) {
            $rettxt .= " " . $hundreds[$key] . " ";
        }
    }

    if ($decnum > 0) {
        $rettxt .= " and ";
        if ($decnum < 20) {
            $rettxt .= $ones[$decnum];
        } elseif ($decnum < 100) {
            $rettxt .= $tens[substr($decnum, 0, 1)];
            $rettxt .= " " . $ones[substr($decnum, 1, 1)];
        }
    }

    // currency word added
    //$currency = session('currency');
    // if($currency=='$'){
    //  $curWord = 'Dollar';
    // }elseif($currency=='€'){
    //  $curWord = 'Euro';
    // }elseif ($currency=='₨') {
    //  $curWord = 'Rupee';
    // }elseif ($currency=='£') {
    //  $curWord = 'Pound';
    // }else{
    //  $curWord = 'MNT';
    // }

    return $rettxt; //.' '.$currency;
}
