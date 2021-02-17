<?php

namespace Simtabi\Laranail\Supports;

use gugglegum\MemorySize\Exception;
use gugglegum\MemorySize\Formatter;
use gugglegum\MemorySize\Parser;


class Humanize
{

    private static $fileSizeFormatter;
    private static $fileSizeParser;
    private static $errors = [];

    /**
     * Create class instance
     *
     * @version      1.0
     * @since        1.0
     */
    private static $instance;
    public static function getInstance() {
        if (isset(self::$instance)) {
            return self::$instance;
        } else {
            self::$instance = new static();
            return self::$instance;
        }
    }

    private function __construct() {}
    private function __clone() {}

    /**
     * @param array $errors
     */
    private static function setErrors(array $errors): void
    {
        self::$errors[] = $errors;

        if (is_object($errors)){
            $errors = TypeConverter::buildArray($errors);
        }elseif (is_array($errors)){
            $errors = Helpers::filterArray($errors);
        }

        if(is_array(self::$errors) && !empty(self::$errors)){
            array_unshift(self::$errors, $errors);
        } else{
            self::$errors = $errors;
        }
    }

    /**
     * @return array
     */
    public static function getErrors(): array
    {
        return self::$errors;
    }

    public static function getParsedFileSize($number){
        try{
            $sp = new Parser();
            return $sp->parse($number);
        }catch (Exception $exception){
            self::setErrors([$exception->getMessage()]);
        }
        return false;
    }

    public static function getFormattedFileSize($number){
        try{
            $sf = new Formatter();
            return $sf->format($number);
        }catch (Exception $exception){
            self::setErrors([$exception->getMessage()]);
        }
        return false;
    }



}
