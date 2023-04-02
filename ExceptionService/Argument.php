<?php
/**
 * == ExceptionService\Argument ==
 * exception handling for invalid arguments
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\ExceptionService;

class Argument extends \Exception {
	
	// class where exception was caught
	protected $fncClass = '';
	
	// name of triggering function
	protected $fncName  = '';
	
	/**
	 * __construct()
	 * initializations
	 *
	 * @param fncClass triggering class
	 * @param fncName triggering function
	 */	
	public function __construct( $fncClass, $fncName, $message, Throwable $previous = null ) {
		$this->fncClass = $fncClass;
		$this->fncName  = $fncName;
		parent::__construct( $message, 0, $previous );
    }
	
	/**
	 * __toString()
	 * text output of the exception
	 */		
	public function __toString() {
        return __CLASS__ . ': Invalid function call. Function: '
			. $this->fncClass . '::' . $this->fncName
			. ' Error message: '
			. $this->message;
    }
	
}

?>