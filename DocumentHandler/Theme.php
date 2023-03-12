<?php
/**
 * DocumentHandler/Theme
 * manage themes in Ancona
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\DocumentHandler;

use Ancona\Ancona as Ancona;
use Ancona\ConfigService as Config;
use Ancona\DocumentService as Document;

class Theme {
	
	protected $themes = [];
	
	/**
	 * __construct()
	 * initializations
	 */		
	public function __construct() {
		// automatically load themes when ThemeHandler is initialized
		$this->loadThemes();
	}
	
	/**
	 * loadThemes()
	 * loads themes by config definition
	 */	
	public function loadThemes() : bool {
		// check if themes are defined
		$themes = Config\framework::get( 'themes', false );
		if ( $themes === false || !is_array( $themes ) ) {
			return false;
		}
		
		// create themes
		foreach ( $themes as $k => $v ) {
			$this->themes[] = new Document\Theme( $k, $v );
		}
		
		return true;
	}
	
	/**
	 * getThemes()
	 * returns an array of loaded themes
	 */		
	public function getThemes() : array {
		return $this->themes;
	}
	
	/**
	 * getThemes()
	 * returns the number of themes available
	 */			
	public function getThemesCount() : number {
		return count( $this->themes );
	}
	
}

?>