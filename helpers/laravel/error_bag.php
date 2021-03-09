<?php

use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;

if (!function_exists('getErrorMsg')) {
    function getErrorMsg($key, $msgClass = 'error-msg', $wrapperClass = 'has-error', $bag = 'errors'): ?string
    {
        if(Session::has($bag)) {
            $errors = Session::get($bag, new MessageBag);
            $wrapperClass  = !empty($wrapperClass) ? " class='$wrapperClass' " : '';
            if ($errors->has($key)) {
                return $errors->first($key, "
                                <div $wrapperClass>
                                     <p class='help-block $msgClass'>:message</p>
                                </div>
                ");
            }
        }
        return '';
    }
}

if (!function_exists('getErrorMsgClass')) {
    function getErrorMsgClass($key, $class = 'error', $bag = 'errors'): ?string
    {
        if(Session::has($bag)) {
            $errors = Session::get($bag, new MessageBag);
            return $errors->has($key) ? $class : '';
        }
        return null;
    }
}

if (!function_exists('getHasErrorCssClass')) {
    function getHasErrorCssClass($key, $class = 'has-error'): ?string
    {
        if(Session::has('errors')) {
            $errors = Session::get('errors', new MessageBag);
            if ($errors->has($key)){
                return " $class ";
            }
        }
        return '';
    }
}
