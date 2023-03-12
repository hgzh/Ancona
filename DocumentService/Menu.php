<?php
/**
 * ##### DocumentService/Menu #####
 * Ancona: Menüs
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\DocumentService;

/**
 * ##### CLASS menu CLASS #####
 * Klasse für Navigationsleisten
 */
class Menu {
	
	protected $code;
	protected $title = '';
	protected $content = false;
	protected $toggle;
	protected $togglePos;
	protected $menuPos;
	
	public const SYS_CONFIG  = 'config';
	public const SYS_ACCOUNT = 'account';
	
	public const POS_START  = 'start';
	public const POS_END    = 'end';
	public const POS_TOP    = 'top';
	public const POS_BOTTOM = 'bottom';
	
	public const TOGGLE_NAV_TOP  = 'nav-top';
	public const TOGGLE_NAV_LEFT = 'nav-left';
	
	/**
	 * __construct()
	 * Klassenkonstruktor
	 *
	 * Parameter:
	 * - code: Kennung des Menüs
	 */		
	public function __construct( $code ) {
		$this->code = $code;
		return $this;
	}
	
	/**
	 * getCode()
	 * gibt den Code zurück
	 */		
	public function getCode() {
		return $this->code;
	}		
	
	/**
	 * setTitle()
	 * setzt den Menütitel auf den angegebenen Wert
	 *
	 * Parameter:
	 * - title: Titel des Menüs
	 */		
	public function setTitle( $title ) {
		$this->title = $title;
		return $this;
	}

	/**
	 * getTitle()
	 * gibt den Menütitel zurück
	 */		
	public function getTitle() {
		return $this->title;
	}	
	
	/**
	 * setContent()
	 * setzt den Menüinhalt auf den angegebenen Wert
	 *
	 * Parameter:
	 * - content: Menüinhalt
	 */		
	public function setContent( $content ) {
		$this->content = $content;
		return $this;
	}	

	/**
	 * getContent()
	 * gibt den Menüinhalt zurück
	 */		
	public function getContent() {
		return $this->content;
	}
	
	/**
	 * setToggle()
	 * setzt den Toggle auf den angegebenen Wert
	 *
	 * Parameter:
	 * - toggle: Menü-Toggle
	 */		
	public function setToggle( $toggle ) {
		$this->toggle = $toggle;
		return $this;
	}
	
	/**
	 * getToggle()
	 * gibt den Toggle zurück
	 */		
	public function getToggle() {
		return $this->toggle;
	}

	/**
	 * setTogglePosition()
	 * setzt die Toggle-Position auf den angegebenen Wert
	 *
	 * Parameter:
	 * - position: Toggle-Position
	 */		
	public function setTogglePosition( $position ) {
		$this->togglePos = $position;
		return $this;
	}
	
	/**
	 * getTogglePosition()
	 * gibt die Toggle-Position zurück
	 */		
	public function getTogglePosition() {
		return $this->togglePos;
	}
	
	/**
	 * setMenuPosition()
	 * setzt die Menü-Position auf den angegebenen Wert
	 *
	 * Parameter:
	 * - position: Menü-Position
	 */		
	public function setMenuPosition( $position ) {
		$this->menuPos = $position;
		return $this;
	}
	
	/**
	 * getMenuPosition()
	 * gibt die Menü-Position zurück
	 */		
	public function getMenuPosition() {
		return $this->menuPos;
	}
	
}

?>