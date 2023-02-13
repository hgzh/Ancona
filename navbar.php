<?php
/**
 * ##### navbar.php #####
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
	
	public const RESTRICTED_ACCOUNT = 'account';
	
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
		
	}
	
	/**
	 * addRestrictedSubnav()
	 * Fügt ein zugriffsbeschränktes Untermenü ein
	 *
	 * Parameter:
	 * - type:   Restriktions-Typ
	 * - label:  Array mit Navigationselementen
	 * - subnav: Untermenü
	 */		
	public function addRestrictedSubnav( $type, $label, $subnav ) {
		
		$struct = $subnav->getStructure();
		if ( count( $struct ) > 0 ) {
			$this->nav[ '!' . $type ]['label'] = $label;
			$this->nav[ '!' . $type ]['*']     = $struct;
		}
		
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