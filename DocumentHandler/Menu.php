<?php
/**
 * ##### DocumentHandler/Menu.php #####
 * Ancona: DocumentHandler für Menüs
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\DocumentHandler;

use Ancona\DocumentService as Document;
use Ancona\HtmlService\Html as Html;
use Ancona\ExceptionService as Exception;

/**
 * ##### CLASS menu CLASS #####
 * Klasse für Menüs
 */
class Menu {
	
	private $context; 
	protected $menus = [];
	
	/**
	 * __construct()
	 * Klassenkonstruktor
	 */		
	public function __construct( $context ) {
		$this->context = $context;
		
		// Standard-Menüs erzeugen
		$this->createStandardMenuAccount();
		$this->createStandardMenuConfig();
	}
	
	/**
	 * attachMenu()
	 * ergänzt ein Menü
	 *
	 * Parameter:
	 * - menu: zu ergänzendes Menüobjekt
	 */	
	public function attachMenu( Document\Menu $menu ) {
		$this->menus[ $menu->getCode() ] = $menu;
	}
	
	/**
	 * getMenu()
	 * gibt ein Menü zurück
	 *
	 * Parameter:
	 * - code: Menücode
	 */		
	public function getMenu( $code ) {
		if ( isset( $this->menus[ $code ] ) ) {
			return $this->menus[ $code ];
		} else {
			throw new Exception\Argument(
				__CLASS__,
				'getMenu()',
				'Menü mit angegebenem Code existiert nicht.'
			);
		}
	}
	
	/**
	 * getMenus()
	 * gibt alle Menüs zurück
	 */			
	public function getMenus() {
		foreach ( $this->menus as $menu ) {
			$return[] = $menu;
		}
		return $return;
	}	
	
	/**
	 * getMenusByMenuPosition()
	 * gibt alle Menüs zurück, die die angegebene Menüposition haben
	 *
	 * Parameter:
	 * - position: Menüposition
	 */			
	public function getMenusByMenuPosition( $position ) {
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
	 * gibt alle Menüs zurück, die die angegebene Toggleposition haben
	 *
	 * Parameter:
	 * - position: Toggleposition
	 */			
	public function getMenusByTogglePosition( $position ) {
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
	 * Darstellung des Standard-Account-Menüs
	 */
	private function createStandardMenuAccount() {
		// Toggle
		$toggle = new Html();
		$toggle->addToggleLink(
			'offcanvas',
			self::getMenuID( Document\Menu::SYS_ACCOUNT ),
			'<i class="bi bi-box-arrow-in-right me-1"></i>Anmelden',
			'btn btn-sm btn-outline-primary mb-1 mb-lg-0 ms-1',
			'Anmelden'
		);
		
		// Menü
		$menuAccount = new Document\Menu( Document\Menu::SYS_ACCOUNT );
		$menuAccount->setTitle( 'Benutzerkonto' )
			->setToggle( $toggle->output() )
			->setTogglePosition( Document\Menu::TOGGLE_NAV_TOP )
			->setMenuPosition( Document\Menu::POS_END );
		
		// Menü hinzufügen
		$this->attachMenu( $menuAccount );
	}

	/**
	 * createStandardMenuConfig()
	 * Darstellung des Standard-Einstellungs-Menüs
	 */
	private function createStandardMenuConfig() {
		// Inhalt
		$content = new Html();
		
		// Theme-Switcher
		if ( count( $this->context->getThemeHandler()->getThemes() ) > 0 ) {
			$content->addHeading( 6, 'Farbmodus', 'text-center');
			$content->openBlock( 'div', 'list-group mt-2' );
			foreach ( $this->context->getThemeHandler()->getThemes() as $theme ) {
				$content->addHTML( Html::elem( 
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
		
		// Toggle
		$toggle = new Html();
		$toggle->addToggleLink(
			'offcanvas',
			self::getMenuID( Document\Menu::SYS_CONFIG ),
			'<i class="bi bi-gear-fill"></i>',
			'btn btn-sm btn-outline-secondary mb-1 mb-lg-0 ms-1',
			'Einstellungen öffnen'
		);
		
		// Menü
		$menuConfig = new Document\Menu( Document\Menu::SYS_CONFIG );
		$menuConfig->setTitle( 'Einstellungen' )
			->setContent( $content->output() )
			->setToggle( $toggle->output() )
			->setTogglePosition( Document\Menu::TOGGLE_NAV_TOP )
			->setMenuPosition( Document\Menu::POS_END );
		
		// Menü hinzufügen
		$this->attachMenu( $menuConfig );		
	}
	
	/**
	 * getMenusHtml()
	 * Menü-Html zusammenstellen und zurückgeben
	 */
	public function getMenusHtml() {
		// Anpassungen an Menüs durchführen
		$this->context->getCustomMenus();
		
		// Html
		$m = new Html();
		
		// aside-Element als Wrapper für die Menüs
		$m->addHTML( Html::elem(
			'aside',
			[ 'id' => 'anc-menu-container' ],
			'',
			false
		));
		
		// Menüs hinzufügen
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
		
		// ausgeben
		return $m->output();
	}

	/**
	 * getMenuID()
	 * gibt den Fragmentbezeichner für das Menü zurück (id=)
	 *
	 * Parameter
	 * - code: Code des Menüs
	 */			
	public static function getMenuID( $code ) {
		return 'ofc-anc-menu-' . $code;
	}		
	
}

?>