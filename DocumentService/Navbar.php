<?php
/**
 * ##### DocumentService/Navbar #####
 * Ancona: Navigationsleisten
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\DocumentService;

/**
 * ##### CLASS navbar CLASS #####
 * Klasse für Navigationsleisten
 */
class Navbar {
	
	protected $nav = [];
	protected $nrDivider = 0;
		
	/**
	 * addEntry()
	 * Eintrag in der Navigationsleiste definieren
	 *
	 * Parameter:
	 * - label:       Beschriftung
	 * - link:        Linkziel
	 */	
	public function addEntry( $label, $link ) {
		$this->nav[ $label ] = $link;
		return $this;
	}
	
	/**
	 * addDivider()
	 * Trenner in der Navigationsleiste definieren
	 */		
	public function addDivider() {
		if ( count( $this->nav ) > 0 ) {
			$this->nav[ '!divider' . $this->nrDivider ] = true;
			$this->nrDivider++;
		}
		return $this;
	}
	
	/**
	 * addSubnav()
	 * Fügt ein Untermenü ein
	 *
	 * Parameter:
	 * - label:  Array mit Navigationselementen
	 * - subnav: Untermenü
	 */		
	public function addSubnav( $label, $subnav ) {
		$struct = $subnav->getStructure();
		if ( count( $struct ) > 0 ) {
			$this->nav[ $label ] = $struct;
		}
		return $this;
	}

	/**
	 * getStructure()
	 * Gibt die Menüstruktur zurück
	 */
	public function getStructure() {
		return $this->nav;
	}
	
}

?>