<?php

namespace Simtabi\Laranail\Exception;

use Exception;

class CatchThisException extends Exception
{

    ## - http://stackoverflow.com/questions/6797142/can-you-throw-an-array-instead-of-a-string-as-an-exception-in-php

    private $__options;
    public  function __construct($message, $code = 0, Exception $previous = null, $options = array('params')){
        parent::__construct($message, $code, $previous);
        $this->__options = $options;
    }

    public function getOptions() {
        return $this->__options;
    }

}
