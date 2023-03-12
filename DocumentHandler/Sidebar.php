<?php
/**
 * DocumentHandler/Sidebar
 * manage sidebars in Ancona
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\DocumentHandler;

use Ancona\Ancona as Ancona;
use Ancona\DocumentService as Document;

class Sidebar {
	
	protected $sidebars = [];
	
	public const SIDEBAR_RIGHT  = 'right';
	public const SIDEBAR_FOOTER = 'footer';
	
	/**
	 * attachSidebar()
	 * sets a Document\Sidebar object to the given position
	 *
	 * @param position position of sidebar content
	 * @param sidebar sidebar object
	 */	
	public function attachSidebar( $position, Document\Sidebar $sidebar ) {
		$this->sidebars[ $position ] = $sidebar;
	}
	
	/**
	 * getSidebar()
	 * returns the Document\Sidebar object of the given position,
	 * or false if no position is set
	 *
	 * @param position position of sidebar content
	 */		
	public function getSidebar( $position ) {
		return $this->sidebars[ $position ] ?? false;
	}

	/**
	 * getSidebarHtml()
	 * returns the html presentation of the Document\Sidebar object
	 *
	 * @param position position of sidebar content
	 */		
	public function getSidebarHtml( $position ) : string {
		$sidebar = $this->getSidebar( $position );
		if ( $sidebar === false ) {
			return '';
		}
		
		return $sidebar->getHtml();
	}	
	
}

?>