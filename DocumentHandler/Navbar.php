<?php
/**
 * ##### DocumentHandler/Navbar.php #####
 * Ancona: DocumentHandler für Navigationen
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\DocumentHandler;

use Ancona\Ancona as Ancona;
use Ancona\ConfigService as Config;
use Ancona\DocumentService as Document;
use Ancona\HtmlService\Html as Html;
use Ancona\ExceptionService as Exception;

/**
 * ##### CLASS Navbar CLASS #####
 * Klasse für Menüs
 */
class Navbar {
	
	private $context; 
	protected $navbars = [];
	
	public const NAV_TOP  = 'top';
	public const NAV_LEFT = 'left';
	
	/**
	 * __construct()
	 * Klassenkonstruktor
	 */		
	public function __construct( $context ) {
		$this->context = $context;
	}
	
	/**
	 * attachNavbar()
	 * ergänzt eine Navigationsleiste
	 *
	 * Parameter:
	 * - position: Ort der Navbar
	 * - navbar:   zu ergänzendes Navbar-Objekt
	 */	
	public function attachNavbar( $position, Document\Navbar $navbar ) {
		$this->navbars[ $position ] = $navbar;
	}
	
	/**
	 * getNavbar()
	 * gibt eine Navigationsleiste zurück
	 *
	 * Parameter:
	 * - position: Position der Navigationsleiste
	 */		
	public function getNavbar( $position ) {
		if ( isset( $this->navbars[ $position ] ) ) {
			return $this->navbars[ $position ];
		} else {
			return false;
		}
	}
	
	/**
	 * getNavbarID()
	 * gibt den Fragmentbezeichner einer Navbar zurück (id=)
	 *
	 * Parameter:
	 * - position: Position der Navigationsleiste
	 * - suffix:   Suffix für Unterelemente
	 */		
	public function getNavbarID( $position, $suffix = false ) {
		$id = 'anc-nav-' . $position;
		if ( $suffix !== false ) {
			$id .= '-' . $suffix;
		}
		return $id;
	}
	
	/**
	 * getNavbarSubID()
	 * gibt den Fragmentbezeichner der Subebene einer Navbar zurück (id=)
	 *
	 * Parameter:
	 * - position: Position der Navigationsleiste
	 * - nr:       Nummer der Subebene
	 * - suffix:   Suffix für Unterelemente
	 */		
	public function getNavbarSubID( $position, $nr, $suffix = false ) {
		$id = 'anc-nav-' . $position . '-sub-' . $nr;
		if ( $suffix !== false ) {
			$id .= '-' . $suffix;
		}
		return $id;
	}	
	
	private function buildNavbarEntries( $position, $active = '' ) {			
		
		// Navigationsleisten-Struktur beziehen
		$nav = $this->getNavbar( $position )->getStructure();

		// neues HTML-Element
		$m = new Html();
		
		// Subtyp bestimmen
		if ( $position === Navbar::NAV_TOP ) {
			$tgOuterClass = 'dropdown';
			$tgElemClass  = 'nav-link dropdown-toggle';
			$tgElemToggle = 'dropdown';
			$tgGroupClass = 'dropdown-menu';
			$tgInnerClass = 'nav-item';
			$tgLinkClass  = 'dropdown-item';
			$tgDividClass = 'dropdown-divider';
			$stInnerClass = 'nav-item';
			$stLinkClass  = 'nav-link';
		} elseif ( $position === Navbar::NAV_LEFT ) {
			$tgOuterClass = '';
			$tgElemClass  = 'nav-link link-dark px-2 btn-toggle';
			$tgElemToggle = 'collapse';
			$tgGroupClass = 'nav nav-pills flex-column small collapse';
			$tgInnerClass = 'nav-item ps-3';
			$tgLinkClass  = 'nav-link link-dark px-2 py-1';
			$tgDividClass = 'nav-item my-2';
			$stInnerClass = 'nav-item';
			$stLinkClass  = 'nav-link link-dark px-2';
		}

		// Einträge anzeigen
		$i = 0;
		foreach ( $nav as $k1 => $v1 ) {
			if ( is_array( $v1 ) == true ) {
				// verschachtelt, Dropdown-Menü öffnen
				$m->openBlock( 'li', 'nav-item ' . $tgOuterClass );
				$m->addHTML( Html::elem(
					'a',
					[
						'class'          => $tgElemClass,
						'href'           => '#',
						'id'             => $this->getNavbarSubID( $position, $i, 'toggle'),
						'role'           => 'button',
						'data-bs-toggle' => $tgElemToggle,
						'data-bs-target' => '#' . $this->getNavbarSubID( $position, $i, 'entries'),
						'aria-haspopup'  => 'true',
						'aria-expanded'  => 'false'
					],
					$k1)
				);

				// Einträge im Dropdown-Menü
				$m->openBlock(
					'ul',
					$tgGroupClass,
					false,
					$this->getNavbarSubID( $position, $i, 'entries')
				);
				foreach ( $v1 as $k2 => $v2 ) {
					$m->openBlock( 'li', $tgInnerClass );
					if ( substr( $k2, 0, 8 ) === '!divider') {
						$m->addInline( 'hr', '', $tgDividClass );
					} else {
						$m->addLink( $v2, $k2, $tgLinkClass );
					}
					$m->closeBlock();
				}
				$m->closeBlock( 2 );
				$i++;
			} else {
				// einfaches Element
				$m->openBlock(
					'li',
					$stInnerClass
						. ( $active == $k1 ? ' active' : '' )
				);
				$m->addLink( $v1, $k1, $stLinkClass );
				$m->closeBlock();
			}
		}

		// Inhalt ausgeben
		return $m->output();
	}
	
	private function getNavTopHtml() {
		// Html
		$m = new Html();
		
		// Fixiert am oberen Bildschirmrand?
		$navClass = '';
		if ( Config\framework::get( 'nav-top-sticky' ) === true ) {
			$navClass .= 'sticky-top';
		}
		
		// Header
		$m->openBlock(
			'header',
			'navbar navbar-expand-lg anc-owncolor ' . $navClass,
			( Config\framework::get( 'nav-top-color', false ) !== false
				? 'background-color:' . Config\framework::get( 'nav-top-color' )
				: false
			),
			$this->getNavbarID( Navbar::NAV_TOP )
		);

		// Container und eigentliche Einträge in der Navigationsleiste
		$m->openBlock( 'nav', 'container', false, $this->getNavbarID( Navbar::NAV_TOP, 'content' ) );

		// Logo
		$m->openBlock( 'div', false, false, $this->getNavbarID( Navbar::NAV_TOP, 'start' ) );
		$m->addLink(
			Ancona::getAbs(),
			'<img src="'
				. Ancona::getAbs()
				. '_images/' . Config\framework::get( 'image-logo' )
				. '" height="35"/>',
			'navbar-brand'
		);
		$m->closeBlock();

		// Navigationsleisten-Einträge (einklappbar)
		$m->openBlock( 'div', 'collapse navbar-collapse', false, $this->getNavbarID( Navbar::NAV_TOP, 'entries' ) );
		$m->openBlock( 'ul', 'navbar-nav flex-grow-1' );
		$m->addHTML( $this->buildNavbarEntries( Navbar::NAV_TOP, '', 'dropdown' ) );
		$m->closeBlock(2);
		
		// ständig sichtbare Elemente
		$m->openBlock( 'div', 'navbar-brand', false, $this->getNavbarID( Navbar::NAV_TOP, 'end' ) );
		
		// Benachrichtigungs-Badge
		$m->addHTML( $this->context->getNotificationBadge() );
	
		// Menü-Toggles
		$menuList = $this->context
			->getMenuHandler()
			->getMenusByTogglePosition( Document\Menu::TOGGLE_NAV_TOP );
		foreach ( $menuList as $menu ) {
			$m->addHTML( $menu->getToggle() );
		}
		$m->closeBlock();
		
		// Aus-/Einklappschalter für mobile Ansichten
		$m->openBlock( 'div', false, false, $this->getNavbarID( Navbar::NAV_TOP, 'toggle' ) );
		$m->addHTML( Html::elem(
			'button',
			[
				'type'           => 'button',
				'id'			 => $this->getNavbarID( Navbar::NAV_TOP, 'toggler' ),
				'class'          => 'navbar-toggler',
				'data-bs-toggle' => 'collapse',
				'data-bs-target' => '#' . $this->getNavbarID( Navbar::NAV_TOP, 'entries' ),
				'aria-controls'  => $this->getNavbarID( Navbar::NAV_TOP, 'entries' ),
				'aria-expanded'  => 'false',
				'aria-label'     => 'Menü ausklappen'
			],
			Html::elem(
				'span',
				[ 'class' => 'navbar-toggler-icon' ] 
			)
		) );
		$m->closeBlock();
		
		// Nav-Container und Header schließen
		$m->closeBlock(2);

		// Inhalt ausgeben
		return $m->output();
	}
	
	private function getNavLeftHtml() {
		// Html
		$m = new Html();
		
		// Aside
		$m->openBlock(
			'aside',
			false,
			false,
			'anc-nav-left'
		);

		// Container und eigentliche Einträge in der Navigationsleiste
		$m->openBlock(
			'nav',
			'offcanvas offcanvas-lg offcanvas-start',
			false,
			$this->getNavbarID( Navbar::NAV_LEFT, 'content' )
		);

		// Titel
		$m->openBlock( 'div', 'offcanvas-header', false, $this->getNavbarID( Navbar::NAV_LEFT, 'start' ) );
		$m->addHeading( 5, Config\framework::get( 'nav-left-title' ), 'offcanvas-title', $this->getNavbarID( Navbar::NAV_LEFT, 'title' ) );
		$m->addHTML( Html::elem(
			'button',
			[
				'type'            => 'button',
				'class'           => 'btn-close',
				'data-bs-dismiss' => 'offcanvas',
				'aria-label'      => 'Schließen'
			] ) );		
		$m->closeBlock();

		// Navigationsleisten-Einträge
		$m->openBlock( 'nav', 'offcanvas-body pe-3', false, $this->getNavbarID( Navbar::NAV_LEFT, 'entries' ) );
		$m->openBlock( 'ul', 'nav nav-pills flex-column' );
		$m->addHTML( $this->buildNavbarEntries( Navbar::NAV_LEFT, '', 'collapse') );
		$m->closeBlock(2);
		
		$m->openBlock( 'div', false, false, $this->getNavbarID( Navbar::NAV_LEFT, 'end' ) );
		// Menü-Toggles
		$menuList = $this->context
			->getMenuHandler()
			->getMenusByTogglePosition( Document\Menu::TOGGLE_NAV_LEFT );
		foreach ( $menuList as $menu ) {
			$m->addHTML( $menu->getToggle() );
		}
		$m->closeBlock();
		
		// Nav-Container und Aside schließen
		$m->closeBlock(2);

		// Inhalt ausgeben
		return $m->output();
	}	
	
	/**
	 * getNavbarHtml()
	 * Navbar-Html zusammenstellen und zurückgeben
	 *
	 * Parameter:
	 * - position: Position der Navigationsleiste
	 */
	public function getNavbarHtml( $position ) {
		
		// wenn nicht definiert, nichts zurückgeben
		if ( !isset( $this->navbars[ $position ] ) ) {
			return '';
		}
		
		if ( $position === Navbar::NAV_TOP ) {
			// Navigationsleiste oben
			return $this->getNavTopHtml();
		} else {
			// Navigationsleiste links
			return $this->getNavLeftHtml();
		}
		
	}
	
	public function getNavbarLeftToggle() {
		if ( $this->getNavbar( Navbar::NAV_LEFT ) === false ) {
			return;
		}
		
		// Html
		$m = new Html();
		
		$m->openRow( false, false, false, 'anc-nav-left-toggle' );
		$m->openCol( false, 12, 'my-2' );
		$m->addToggleLink(
			'offcanvas',
			'anc-nav-left-content',
			Config\framework::get( 'nav-left-toggle-text' ),
			'd-grid btn btn-sm btn-outline-secondary'
		);
		$m->closeCol();
		$m->closeRow();
		
		return $m->output();
	}

}

?>