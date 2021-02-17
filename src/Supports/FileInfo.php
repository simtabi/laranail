<?php

namespace Simtabi\Laranail\Supports;

use VincoWeb\FileInfo\FileInfo as F;

class FileInfo
{

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


    public function getFile(){
        return new FileInfo();
    }

    public function isRemoteFile($link){
        $finfo = new F();
        return !$finfo->get($link) ? false : true;
    }

    public function isRemoteFileImage($link){
        $finfo = new F();
        return !$finfo->isImage($link) ? false : true;
    }

    public function remoteFileInfo($link){
        $finfo = new F();
        return $finfo->get($link);
    }

}
