<?php
/**
 * ##### DocumentService/Theme #####
 * Ancona: Farbthemes
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\DocumentService;

/**
 * ##### CLASS Theme CLASS #####
 * Klasse für Farbthemes
 */
class Theme {
	
	private string $code;
	private string $title;
	
	/**
	 * __construct()
	 * Klassenkonstruktor
	 *
	 * Parameter:
	 * - code:  Kennung des Themes
	 * - title: Titel des Themes
	 */	
	public function __construct( $code, $title ) {
		$this->code  = $code;
		$this->title = $title;
	}
	
	/**
	 * getCode()
	 * gibt den Code des Themes zurück
	 */		
	public function getCode(): string {
		return $this->code;
	}

	/**
	 * setCode()
	 * setzt den Code des Themes
	 */		
	public function setCode( $code ) {
		$this->code = $code;
	}	
	
	/**
	 * getTitle()
	 * gibt den Titel des Themes zurück
	 */		
	public function getTitle(): string {
		return $this->title;
	}

	/**
	 * setTitle()
	 * setzt den Titel des Themes
	 */		
	public function setTitle( $title ) {
		$this->title = $title;
	}	
	
}

?>