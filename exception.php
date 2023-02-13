<?php
/**
 * ##### exception.php #####
 * hgzWeb: Ausnahmebehandlung
 *
 * (C) 2023 Hgzh
 *
 */

namespace hgzWeb\ExceptionService;

/**
 * ##### CLASS Database CLASS #####
 * Ausnahmebehandlung von Datenbankfehlern
 */
class Database extends \Exception {
	
	public function __construct( $message, $code, Throwable $previous = null ) {
		parent::__construct( $message, $code, $previous );
    }
	
	public function __toString() {
        return __CLASS__ . ': Fehler beim Zugriff auf die Datenbank. Fehlercode: '
			. $this->code
			. ' Fehlerbeschreibung: '
			. $this->message;
    }
	
}

/**
 * ##### CLASS Database CLASS #####
 * Ausnahmebehandlung von Argumentfehlern
 */
class Argument extends \Exception {
	
	protected $fncClass = '';
	protected $fncName  = '';
	
	public function __construct( $fncClass, $fncName, $message, Throwable $previous = null ) {
		$this->fncClass = $fncClass;
		$this->fncName  = $fncName;
		parent::__construct( $message, 0, $previous );
    }
	
	public function __toString() {
        return __CLASS__ . ': Fehlerhafter Funktionsaufruf. Funktion: '
			. $this->fncClass . '::' . $this->fncName
			. ' Fehlerbeschreibung: '
			. $this->message;
    }
	
}

/**
 * ##### CLASS Database CLASS #####
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