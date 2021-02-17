<?php

if (!function_exists('format_number')) {
    function format_number($number, $decimals = 0): string
    {
        return number_format($number, $decimals);
    }
}



if (!function_exists('format_directory')) {

    /**
     * Replace date variable in dir path.
     *
     * @param string $dir
     *
     * @return string
     */
    function format_directory(string $dir)
    {
        $replacements = [
            '{Y}' => date('Y'),
            '{m}' => date('m'),
            '{d}' => date('d'),
            '{H}' => date('H'),
            '{i}' => date('i'),
            '{s}' => date('s'),
        ];

        return str_replace(array_keys($replacements), $replacements, $dir);
    }
}