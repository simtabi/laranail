<?php

namespace Simtabi\Laranail\Supports;

use Spatie\Url\Url;

class URLFactory
{

    public static function appendScheme($url, $https = false){
        $url = Url::fromString($url);

        // if no scheme, append
        if (!$url->getScheme() == 'https' && !$url->getScheme() == 'http'){
            $url = (!$https ? 'http://' : 'https://') . $url;
        }

        return $url;
    }


    public static function encodeURL($url) {
        $reserved = [
            ':'  => '!%3A!ui',
            '/'  => '!%2F!ui',
            '?'  => '!%3F!ui',
            '#'  => '!%23!ui',
            '['  => '!%5B!ui',
            ']'  => '!%5D!ui',
            '@'  => '!%40!ui',
            '!'  => '!%21!ui',
            '$'  => '!%24!ui',
            '&'  => '!%26!ui',
            '\'' => '!%27!ui',
            '('  => '!%28!ui',
            ')'  => '!%29!ui',
            '*'  => '!%2A!ui',
            '+'  => '!%2B!ui',
            ','  => '!%2C!ui',
            ';'  => '!%3B!ui',
            '='  => '!%3D!ui',
            '%'  => '!%25!ui',
        ];
        $url = rawurlencode($url);
        $url = preg_replace(array_values($reserved), array_keys($reserved), $url);
        return $url;
    }

    /**
     * Function add_http
     *
     * @param $url
     * @param bool|false $https
     * @return string
     *
     * Handcrafted with LOVE & PRIDE in NAIROBI, KENYA
     * @author http://stackoverflow.com/questions/2762061/how-to-add-http-if-its-not-exists-in-the-url
     */
    public static function addHttp($url, $https = false) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            if($https){
                $url = "https://" . $url;
            }else{
                $url = "http://" . $url;
            }
        }
        return $url;
    }

    /**
     * Function remove_http
     *
     * @param $url
     * @return mixed
     *
     * Handcrafted with LOVE & PRIDE in NAIROBI, KENYA
     * @author http://stackoverflow.com/questions/4357668/how-do-i-remove-http-https-and-slash-from-user-input-in-php
     */
    public static function removeHttp($url) {
        /**
         *
         *
        $disallowed = array('http://', 'https://');
        foreach($disallowed as $d) {
        if(strpos($url, $d) === 0) {
        return str_replace($d, '', $url);
        }
        }
        return $url;
         */

        return preg_replace('#^https?://#', '', $url);
    }

    /**
     * Function format_url
     *
     * @param $url
     * @param bool|true $formatted
     * @param bool|false $https
     * @return string
     *
     * Handcrafted with LOVE & PRIDE in NAIROBI, KENYA
     * @author Imani Manyara <www.imanimanyara.com>
     */
    public static function formatUrl($url, $formatted = true, $https = false){

        $url = self::addHttp(self::removeHttp($url), $https);
        if(!$formatted){
            $output = self::removeHttp($url);
        }else{
            $output = $url;
        }

        return $output;
    }

}
