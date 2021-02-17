<?php

if (!function_exists('baseUrl')) {
    function baseUrl(){

        // First we need to get the protocol the website is using
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] === 443) ? "https://" : "http://";

        // Build URL
        $baseURL  = $protocol.$_SERVER['HTTP_HOST'].'/';

        // Append project root folder/project-folder or yourdomain.com/foldername
        $baseURL .= preg_replace('@/+$@','',dirname($_SERVER['SCRIPT_NAME'])).'/';
        // $baseURL .= rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') .'/';


        // Ensure we always have a trailing slash,
        // but first we trim all existing ones,
        // then we append to ensure consistency
        $baseURL = trim($baseURL, '/').'/';

        return $baseURL;
    }
}

