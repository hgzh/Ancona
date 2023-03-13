<?php
/**
 * == DocumentService/Menu ==
 * menu elements in Ancona
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\DocumentService;

class Menu {
	
	// menu code
	protected $code;
	
	// menu title
	protected $title = '';
	
	// content html
	protected $content = false;
	
	// toggle html
	protected $toggle;
	
	// toggle position (one of TOGGLE_NAV_TOP / TOGGLE_NAV_LEFT)
	protected $togglePos;
	
	// menu position (one of POS_*)
	protected $menuPos;
	
	// system menus
	public const SYS_CONFIG  = 'config';
	public const SYS_ACCOUNT = 'account';
	
	// menu positions
	public const POS_START  = 'start';
	public const POS_END    = 'end';
	public const POS_TOP    = 'top';
	public const POS_BOTTOM = 'bottom';
	
	// toggle position
	public const TOGGLE_NAV_TOP  = 'nav-top';
	public const TOGGLE_NAV_LEFT = 'nav-left';
	
	/**
	 * __construct()
	 * initializations
	 *
	 * @param code menu code
	 */		
	public function __construct( $code ) {
		$this->code = $code;
		return $this;
	}
	
	/**
	 * getCode()
	 * returns the menu code
	 */		
	public function getCode() : string {
		return $this->code;
	}		
	
	/**
	 * setTitle()
	 * sets the menu title to the given value
	 *
	 * @param title new menu title
	 */		
	public function setTitle( $title ) : Menu {
		$this->title = $title;
		return $this;
	}

	/**
	 * getTitle()
	 * returns the menu title
	 */		
	public function getTitle() : string {
		return $this->title;
	}	
	
	/**
	 * setContent()
	 * sets the menu content html
	 *
	 * @param content new menu content
	 */		
	public function setContent( $content ) : Menu {
		$this->content = $content;
		return $this;
	}	

	/**
	 * getContent()
	 * returns the menu content html
	 */		
	public function getContent() : string {
		return $this->content;
	}
	
	/**
	 * setToggle()
	 * sets the toggle html
	 *
	 * @param toggle toggle html
	 */		
	public function setToggle( $toggle ) : Menu {
		$this->toggle = $toggle;
		return $this;
	}
	
	/**
	 * getToggle()
	 * returns the menu toggle html
	 */		
	public function getToggle() : string {
		return $this->toggle;
	}

	/**
	 * setTogglePosition()
	 * sets the menu toggle position
	 *
	 * @param position toggle position
	 */		
	public function setTogglePosition( $position ) : Menu {
		$this->togglePos = $position;
		return $this;
	}
	
	/**
	 * getTogglePosition()
	 * returns the menu toggle position
	 */		
	public function getTogglePosition() : string {
		return $this->togglePos;
	}
	
	/**
	 * setMenuPosition()
	 * sets the menu position
	 *
	 * @param position menu position
	 */		
	public function setMenuPosition( $position ) : Menu {
		$this->menuPos = $position;
		return $this;
	}
	
	/**
	 * getMenuPosition()
	 * returns the menu position
	 */		
	public function getMenuPosition() : string {
		return $this->menuPos;
	}
	
}

?>