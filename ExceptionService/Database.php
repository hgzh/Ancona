<?php
/**
 * == ExceptionService\Database ==
 * exception handling for database errors
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\ExceptionService;

class Database extends \Exception {

	/**
	 * __construct()
	 * initializations
	 */	
	public function __construct( $message, $code, Throwable $previous = null ) {
		parent::__construct( $message, $code, $previous );
    }

	/**
	 * __toString()
	 * text output of the exception
	 */		
	public function __toString() {
        return __CLASS__ . ': Failed database access. Error code: '
			. $this->code
			. ' Error message: '
			. $this->message;
    }
	
}

?>