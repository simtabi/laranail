<?php

use GpsLab\Component\Base64UID\Base64UID;

if (!function_exists('base64Uid')) {
    /**
     * @param int $length
     * @param string $charset
     * @return mixed
     */
    function base64Uid($length = 12, $charset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'){
        return (new RandomCharGenerator($length, $charset))->generate();
    }
}

