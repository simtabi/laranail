<?php

use Carbon\Carbon;

if (!function_exists('format_time')) {
    /**
     * @param Carbon $timestamp
     * @param string $format
     * @return string
     */
    function format_time(Carbon $timestamp, $format = 'j M Y H:i')
    {
        $first = Carbon::create(0000, 0, 0, 00, 00, 00);
        if ($timestamp->lte($first)) {
            return '';
        }

        return $timestamp->format($format);
    }
}

if (!function_exists('getFormattedTime')) {

    function getFormattedTime($baseTime = null, string $format = 'M jS, Y H:i T', string $timezone = 'America/New_York'): string
    {
        try {

            // if we are given a timestamp
            if (isTimestampAlt($baseTime)){
                $timeNow  = new DateTime();
                $timeNow->setTimestamp($baseTime);
            }else{
                $timeNow  = new DateTime($baseTime);
            }

            // set timezone
            $timezone = new DateTimeZone($timezone);
            $timeNow->setTimezone($timezone);

            if (isTimestampAlt($baseTime)){
                $timeNow->setTimestamp($baseTime);
            }

            return $timeNow->format($format);
        }catch (Exception $exception){
            throw new RuntimeException("Something is wrong with your time" . $exception->getMessage());
        }

    }
}

if (!function_exists('date_from_database')) {
    /**
     * @param string $time
     * @param string $format
     * @return string
     */
    function date_from_database(string $time, $format = 'Y-m-d')
    {
        if (empty($time)) {
            return $time;
        }
        return format_time(Carbon::parse($time), $format);
    }
}