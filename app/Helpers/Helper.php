<?php

use App\Models\Country;
use App\Models\Template;
use Illuminate\Http\Request;

if(!function_exists('nice_date')) {
    function nice_date($value='',$format='d/m/Y') {
        return date($format,strtotime($value));
    }
}

if(!function_exists('dateDiffInDays')) {
    function dateDiffInDays($date1, $date2) { 
        // Calulating the difference in timestamps 
        $diff = strtotime($date2) - strtotime($date1); 
          
        // 1 day = 24 hours 
        // 24 * 60 * 60 = 86400 seconds 
        return abs(round($diff / 86400)); 
    }
}

if (!function_exists('date_difference')) {
    function date_difference($date1, $date2, $format = '%a') {
        $datetime_1 = date_create($date1);
        $datetime_2 = date_create($date2);
        $diff = date_diff($datetime_1, $datetime_2);
        return $diff->format($format);
    }
}

if(!function_exists('str_limit')) {
    function str_limit($value, $limit = 100, $end = '...') {
        if (mb_strwidth($value, 'UTF-8') <= $limit) {
            return $value;
        }

        return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')).$end;
    }
}

if(!function_exists('set_active')) {
	function set_active($path, $active = 'active') {
		if( is_array($path) ) {
			return call_user_func_array('Request::is', (array)$path) ? $active : '';
		}
		return request()->path() == $path ? $active : '';
	}
}

if (!function_exists('getCountry')) {
    function getCountry($id) {
    	$country = Country::where('id',$id)->first();
        return $country;
    }
}

?>