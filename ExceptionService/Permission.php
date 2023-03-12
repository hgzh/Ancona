<?php
/**
 * == ExceptionService\Permission ==
 * exception handling for missing permissions
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\ExceptionService;

class Permission extends \Exception {
	
	// keys of missing permissions
	protected $fncPermissions = [];
	
	/**
	 * __construct()
	 * initializations
	 *
	 * @param fncPermissions keys of missing permissions
	 */		
	public function __construct( array $fncPermissions, $message, Throwable $previous = null ) {
		$this->fncPermissions = $fncPermissions;
		parent::__construct( $message, 0, $previous );
    }

	/**
	 * __toString()
	 * text output of the exception
	 */		
	public function __toString() {
        return __CLASS__ . ': Missing permission '
			. $this->fncPermission;
    }
	
}

?>