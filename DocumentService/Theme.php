<?php
/**
 * == DocumentService/Theme ==
 * color themes in Ancona
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\DocumentService;

class Theme {
	
	// theme code
	private string $code;
	
	// theme title
	private string $title;
	
	/**
	 * __construct()
	 * initializations
	 *
	 * @param code theme code
	 * @param title theme title
	 */	
	public function __construct( $code, $title ) {
		$this->code  = $code;
		$this->title = $title;
	}
	
	/**
	 * getCode()
	 * retuns the theme code
	 */		
	public function getCode(): string {
		return $this->code;
	}

	/**
	 * setCode()
	 * sets the theme code
	 *
	 * @param code theme code
	 */		
	public function setCode( $code ) : Theme {
		$this->code = $code;
		return $this;
	}	
	
	/**
	 * getTitle()
	 * returns the theme title
	 */		
	public function getTitle(): string {
		return $this->title;
	}

	/**
	 * setTitle()
	 * sets the theme title
	 *
	 * @param title theme title
	 */		
	public function setTitle( $title ) : Theme {
		$this->title = $title;
		return $this;
	}	
	
}

?>