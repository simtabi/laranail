<?php

use PHLAK\StrGen\Generator;
use PHLAK\StrGen\CharSet;


if (!function_exists('generate_password')) {
    function generate_password($length = 10)
    {
        $generator = new Generator();
        return $generator->charset([CharSet::MIXED_ALPHA, CharSet::NUMERIC])->length($length)->generate();
    }
}

if (!function_exists('generate_random_string')) {

    function generate_random_string($length = 16, $level = 5){
        $str = '';
        for($i = 0; $i < $level; $i++){
            $str = base64_encode(md5(microtime(true)).$str);
        }
        return substr($str, 0, $length);
    }
}

if (!function_exists('generate_file_name')) {

    function generate_file_name($oldName = null, $appendTime = true, $keepOldName = true, $hash = true){

        if (empty($oldName)) {
            $keepOldName = false;
            $fileName    = generate_random_string(20, 10);
        }else{
            $fileName    = preg_replace('/\s+/', '_', ($appendTime ? time() : '') . '' . $oldName);
        }
        $fileName        = $keepOldName ? $fileName : (($appendTime ? time() : '') . '' .generate_random_string(16));

        if ($hash) {
            return  $fileName;
        }
        return sha1(md5($fileName));
    }
}

if (!function_exists('generate_numbers_in_range')) {
    function generate_numbers_in_range($end, $start = 0, $step = 10){
        // http://php.net/manual/en/function.range.php
        $ranges = [];
        foreach ( range($start, $end, $step) as $item ) {
            $ranges[] = $item;
        }
        return $ranges;
    }
}

if (!function_exists('generate_copyright_year')) {

    function generate_copyright_year($startYear = null){
        if(empty(intval($startYear))){
            $startYear = date('Y');
        }else
        {
            if(intval($startYear) == date('Y')){
                return intval($startYear);
            }
            if(intval($startYear) < date('Y')){
                return intval($startYear) . ' — ' . date('Y');
            }
            if(intval($startYear) > date('Y')){
                return date('Y');
            }
        }
        return  $startYear;
    }
}