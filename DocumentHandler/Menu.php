<?php
/**
 * == DocumentHandler/Menu ==
 * handling of menus in Ancona
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\DocumentHandler;

use Ancona\DocumentService as Document;
use Ancona\HtmlService as Html;
use Ancona\ExceptionService as Exception;

class Menu {
	
	// Ancona class context
	private $context;
	
	// attached menus
	protected $menus = [];
	
	/**
	 * __construct()
	 * initializations
	 *
	 * @param context Ancona context
	 */		
	public function __construct( $context ) {
		// set context
		$this->context = $context;
		
		// create standard menus
		$this->createStandardMenuAccount();
		$this->createStandardMenuConfig();
	}
	
	/**
	 * attachMenu()
	 * binds a menu object
	 *
	 * @param menu menu object to attach
	 */	
	public function attachMenu( Document\Menu $menu ) {
		$this->menus[ $menu->getCode() ] = $menu;
	}
	
	/**
	 * removeMenu()
	 * removes a menu object
	 *
	 * @param code menu code
	 */	
	public function removeMenu( $code ) {
		unset( $this->menus[ $code ] );
	}	
	
	/**
	 * getMenu()
	 * returns a menu object
	 *
	 * @param code menu code
	 */		
	public function getMenu( $code ) : Document\Menu {
		if ( isset( $this->menus[ $code ] ) ) {
			return $this->menus[ $code ];
		} else {
			throw new Exception\Argument(
				__CLASS__,
				'getMenu()',
				'Menu with given code does not exist.'
			);
		}
	}
	
	/**
	 * getMenus()
	 * returns all menus as an array
	 */			
	public function getMenus() : array {
		$return = [];
		foreach ( $this->menus as $menu ) {
			$return[] = $menu;
		}
		return $return;
	}	
	
	/**
	 * getMenusByMenuPosition()
	 * returns all menus with given menu position
	 *
	 * @param position menu position
	 */			
	public function getMenusByMenuPosition( $position ) : array {
		$filter = [];
		foreach ( $this->menus as $menu ) {
			if ( $menu->getMenuPosition() == $position && $menu->getContent() !== false ) {
				$filter[] = $menu;
			}
		}
		return $filter;
	}

	/**
	 * getMenusByTogglePosition()
	 * returns all menus with given toggle position
	 *
	 * @param position: toggle position
	 */			
	public function getMenusByTogglePosition( $position ) : array {
		$filter = [];
		foreach ( $this->menus as $menu ) {
			if ( $menu->getTogglePosition() == $position && $menu->getContent() !== false ) {
				$filter[] = $menu;
			}
		}
		return $filter;
	}
	
	/**
	 * createStandardMenuAccount()
	 * creates the standard account menu
	 */
	private function createStandardMenuAccount() {
		// toggle
		$toggle = new Html\Html();
		$toggle->addToggleLink(
			'offcanvas',
			self::getMenuID( Document\Menu::SYS_ACCOUNT ),
			'<i class="bi bi-box-arrow-in-right me-1"></i>Anmelden',
			'btn btn-sm btn-outline-primary mb-1 mb-lg-0 ms-1',
			'Anmelden'
		);
		
		// menu
		$menuAccount = new Document\Menu( Document\Menu::SYS_ACCOUNT );
		$menuAccount->setTitle( 'Benutzerkonto' )
			->setToggle( $toggle->output() )
			->setTogglePosition( Document\Menu::TOGGLE_NAV_TOP )
			->setMenuPosition( Document\Menu::POS_END );
		
		// attach menu
		$this->attachMenu( $menuAccount );
	}

	/**
	 * createStandardMenuConfig()
	 * creates the standard config menu
	 */
	private function createStandardMenuConfig() {
		// content
		$content = new Html\Html();
		
		// theme switcher
		if ( count( $this->context->getThemeHandler()->getThemes() ) > 0 ) {
			$content->addHeading( 6, 'Farbmodus', 'text-center');
			$content->openBlock( 'div', 'list-group mt-2' );
			foreach ( $this->context->getThemeHandler()->getThemes() as $theme ) {
				$content->addHTML( Html\Html::elem( 
					'button',
					[
						'class'               => 'list-group-item list-group-item-action',
						'type'                => 'button',
						'data-bs-theme-value' => $theme->getCode()
					],
					$theme->getTitle()
				) );
			}
			$content->closeBlock();
		}
		
		// toggle
		$toggle = new Html\Html();
		$toggle->addToggleLink(
			'offcanvas',
			self::getMenuID( Document\Menu::SYS_CONFIG ),
			'<i class="bi bi-gear-fill"></i>',
			'btn btn-sm btn-outline-secondary mb-1 mb-lg-0 ms-1',
			'Einstellungen öffnen'
		);
		
		// menu
		$menuConfig = new Document\Menu( Document\Menu::SYS_CONFIG );
		$menuConfig->setTitle( 'Einstellungen' )
			->setContent( $content->output() )
			->setToggle( $toggle->output() )
			->setTogglePosition( Document\Menu::TOGGLE_NAV_TOP )
			->setMenuPosition( Document\Menu::POS_END );
		
		// attach menu
		$this->attachMenu( $menuConfig );		
	}
	
	/**
	 * getMenusHtml()
	 * returns the html of the menus
	 */
	public function getMenusHtml() : string {
		// get menu customizations
		$this->context->getCustomMenus();
		
		// html
		$m = new Html\Html();
		
		// aside element wrapping the menus
		$m->addHTML( Html\Html::elem(
			'aside',
			[ 'id' => 'anc-menu-container' ],
			'',
			false
		));
		
		// add menu
		foreach ( $this->menus as $menu ) {
			if ( $menu->getContent() !== false ) {
				$m->addOffcanvas(
					'anc-menu-' . $menu->getCode(),
					$menu->getTitle(),
					$menu->getContent(),
					$menu->getMenuPosition()
				);	
			}
		}
		
		$m->addHTML('</aside>');
		
		// output
		return $m->output();
	}

	/**
	 * getMenuID()
	 * returns the id= of the menu
	 *
	 * @param code menu code
	 */			
	public static function getMenuID( $code ) : string {
		return 'ofc-anc-menu-' . $code;
	}		
	
}

?>