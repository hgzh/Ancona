<?php
/**
 * ##### DocumentService/Sidebar #####
 * Ancona: Seitenbereiche
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\DocumentService;

/**
 * ##### CLASS Sidebar CLASS #####
 * Klasse für Seitenbereiche
 */
class Sidebar {
	
	private string $html;
	
	/**
	 * __construct()
	 * Klassenkonstruktor
	 *
	 * Parameter:
	 * - html: HTML-Daten des Seitenbereichs
	 */	
	public function __construct( $html ) {
		$this->html = $html;
	}
	
	/**
	 * getHtml()
	 * gibt das HTML des Seitenbereichs zurück
	 */		
	public function getHtml(): string {
		return $this->html;
	}

	/**
	 * setHtml()
	 * setzt das HTML des Seitenbereichs
	 *
	 * Parameter:
	 * - html: HTML-Daten des Seitenbereichs
	 */		
	public function setHtml( $html ) {
		$this->html = $html;
		return $this;
	}
	
}

?>