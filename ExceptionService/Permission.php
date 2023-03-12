<?php
/**
 * ##### exception.php #####
 * Ancona: Ausnahmebehandlung für Berechtigungsfehler
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\ExceptionService;

/**
 * ##### CLASS Permission CLASS #####
 * Ausnahmebehandlung von Berechtigungsfehlern
 */
class Permission extends \Exception {
	
	protected $fncPermissions = [];
	
	public function __construct( array $fncPermissions, $message, Throwable $previous = null ) {
		$this->fncPermissions = $fncPermissions;
		parent::__construct( $message, 0, $previous );
    }
	
	public function __toString() {
        return __CLASS__ . ': Fehlende Berechtigung '
			. $this->fncPermission;
    }
	
}

?>