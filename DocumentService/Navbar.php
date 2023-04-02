<?php
/**
 * == DocumentService/Navbar ==
 * navbar elements in Ancona
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\DocumentService;

class Navbar {
	
	// navbar entries
	protected $nav = [];
	
	// divider counter
	protected $nrDivider = 0;
		
	/**
	 * addEntry()
	 * define navbar entry
	 *
	 * @param label entry label
	 * @prama link entry link
	 */	
	public function addEntry( $label, $link ) : Navbar {
		$this->nav[ $label ] = $link;
		return $this;
	}
	
	/**
	 * addDivider()
	 * define navbar divider in current position
	 */		
	public function addDivider() : Navbar {
		if ( count( $this->nav ) > 0 ) {
			$this->nav[ '!divider' . $this->nrDivider ] = true;
			$this->nrDivider++;
		}
		return $this;
	}
	
	/**
	 * addSubnav()
	 * adds a subnav level with entries
	 *
	 * @param label entry label
	 * @param subnav subnav structure
	 */		
	public function addSubnav( $label, $subnav ) : Navbar {
		$struct = $subnav->getStructure();
		if ( count( $struct ) > 0 ) {
			$this->nav[ $label ] = $struct;
		}
		return $this;
	}

	/**
	 * getStructure()
	 * returns the navbar entry structure
	 */
	public function getStructure() : array {
		return $this->nav;
	}
	
}

?>