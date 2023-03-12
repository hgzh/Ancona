<?php
/**
 * ##### exception.php #####
 * Ancona: Ausnahmebehandlung für Argumentfehler
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\ExceptionService;

/**
 * ##### CLASS Argument CLASS #####
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

?>