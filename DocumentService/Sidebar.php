<?php
/**
 * == DocumentService/Sidebar ==
 * sidebar elements in Ancona
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\DocumentService;

class Sidebar {
	
	// sidebar html
	private string $html;
	
	/**
	 * __construct()
	 * initializations
	 *
	 * @param html html data
	 */	
	public function __construct( $html ) {
		$this->html = $html;
	}
	
	/**
	 * getHtml()
	 * returns the html data of the sidebar
	 */		
	public function getHtml(): string {
		return $this->html;
	}

	/**
	 * setHtml()
	 * sets the sidebar html
	 *
	 * @param html new html data
	 */		
	public function setHtml( $html ) : Sidebar {
		$this->html = $html;
		return $this;
	}
	
}

?>