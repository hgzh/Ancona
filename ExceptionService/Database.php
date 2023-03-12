<?php
/**
 * ##### exception.php #####
 * Ancona: Ausnahmebehandlung für Datenbankfehler
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\ExceptionService;

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

?>