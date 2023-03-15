<?php
/**
 * == DocumentHandler/Navbar ==
 * navbar handling in ancona
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\DocumentHandler;

use Ancona\Ancona as Ancona;
use Ancona\ConfigService as Config;
use Ancona\DocumentService as Document;
use Ancona\HtmlService as Html;
use Ancona\ExceptionService as Exception;

class Navbar {
	
	// Ancona context
	private $context;
	
	// navbar list
	protected $navbars = [];
	
	// navbar types
	public const NAV_TOP  = 'top';
	public const NAV_LEFT = 'left';
	
	/**
	 * __construct()
	 * initializations
	 *
	 * @param context Ancona context
	 */		
	public function __construct( $context ) {
		// set Ancona context
		$this->context = $context;
	}
	
	/**
	 * attachNavbar()
	 * adds a navbar
	 *
	 * @param position navbar position (one of NAV_TOP, NAV_LEFT)
	 * @param navbar navbar object to add
	 */	
	public function attachNavbar( $position, Document\Navbar $navbar ) {
		$this->navbars[ $position ] = $navbar;
	}
	
	/**
	 * getNavbar()
	 * returns a navbar object
	 *
	 * @param position navbar position
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
	 * gets the id= of the navbar
	 *
	 * @param position navbar position
	 * @param suffix suffix for children
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
	 * gets the id= of the sub element of the navbar
	 *
	 * @param position navbar position
	 * @param nr number of sub-level
	 * @param suffix suffix for children
	 */
	public function getNavbarSubID( $position, $nr, $suffix = false ) {
		$id = 'anc-nav-' . $position . '-sub-' . $nr;
		if ( $suffix !== false ) {
			$id .= '-' . $suffix;
		}
		return $id;
	}	
	
	/**
	 * buildNavbarEntries()
	 * creates the entries in the navbar
	 *
	 * @param position navbar position
	 * @param active indicates an active element
	 */	
	private function buildNavbarEntries( $position, $active = '' ) {			
		
		// get navbar structure
		$nav = $this->getNavbar( $position )->getStructure();

		// html
		$m = new Html\Html();
		
		// determine subtype
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

		// show entries
		$i = 0;
		foreach ( $nav as $k1 => $v1 ) {
			if ( is_array( $v1 ) == true ) {
				// nested, open dropdown
				$m->openBlock( 'li', 'nav-item ' . $tgOuterClass );
				$m->addHTML( Html\Html::elem(
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

				// dropdown entries
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
				// simple element
				$m->openBlock(
					'li',
					$stInnerClass
						. ( $active == $k1 ? ' active' : '' )
				);
				$m->addLink( $v1, $k1, $stLinkClass );
				$m->closeBlock();
			}
		}

		// return content
		return $m->output();
	}
	
	/**
	 * getNavTopHtml()
	 * returns the outer html for the top navbar
	 */		
	private function getNavTopHtml() {
		// html
		$m = new Html\Html();
		
		// fixed on top of viewport?
		$navClass = '';
		if ( Config\framework::get( 'nav-top-sticky' ) === true ) {
			$navClass .= 'sticky-top';
		}
		
		// header
		$m->openBlock(
			'header',
			'navbar navbar-expand-lg anc-owncolor ' . $navClass,
			( Config\framework::get( 'nav-top-color', false ) !== false
				? 'background-color:' . Config\framework::get( 'nav-top-color' )
				: false
			),
			$this->getNavbarID( Navbar::NAV_TOP )
		);

		// container and navbar entries
		$m->openBlock( 'nav', 'container', false, $this->getNavbarID( Navbar::NAV_TOP, 'content' ) );

		// logo
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

		// collapsable navbar entries
		$m->openBlock( 'div', 'collapse navbar-collapse', false, $this->getNavbarID( Navbar::NAV_TOP, 'entries' ) );
		$m->openBlock( 'ul', 'navbar-nav flex-grow-1' );
		$m->addHTML( $this->buildNavbarEntries( Navbar::NAV_TOP, '', 'dropdown' ) );
		$m->closeBlock(2);
		
		// always visible navbar entries
		$m->openBlock( 'div', 'navbar-brand', false, $this->getNavbarID( Navbar::NAV_TOP, 'end' ) );
		
		// notification badge
		$m->addHTML( $this->context->getNotificationBadge() );
	
		// menu toggles
		$menuList = $this->context
			->getMenuHandler()
			->getMenusByTogglePosition( Document\Menu::TOGGLE_NAV_TOP );
		foreach ( $menuList as $menu ) {
			$m->addHTML( $menu->getToggle() );
		}
		$m->closeBlock();
		
		// toggles for mobile viewports
		$m->openBlock( 'div', false, false, $this->getNavbarID( Navbar::NAV_TOP, 'toggle' ) );
		$m->addHTML( Html\Html::elem(
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
			Html\Html::elem(
				'span',
				[ 'class' => 'navbar-toggler-icon' ] 
			)
		) );
		$m->closeBlock();
		
		// close nav container and header
		$m->closeBlock(2);

		// return content
		return $m->output();
	}
	
	/**
	 * getNavLeftHtml()
	 * returns the outer html for the left navbar
	 */			
	private function getNavLeftHtml() {
		// html
		$m = new Html\Html();
		
		// aside element
		$m->openBlock(
			'aside',
			false,
			false,
			'anc-nav-left'
		);

		// container and navbar entries
		$m->openBlock(
			'nav',
			'offcanvas offcanvas-lg offcanvas-start',
			false,
			$this->getNavbarID( Navbar::NAV_LEFT, 'content' )
		);

		// title
		$m->openBlock( 'div', 'offcanvas-header', false, $this->getNavbarID( Navbar::NAV_LEFT, 'start' ) );
		$m->addHeading( 5, Config\framework::get( 'nav-left-title' ), 'offcanvas-title', $this->getNavbarID( Navbar::NAV_LEFT, 'title' ) );
		$m->addHTML( Html\Html::elem(
			'button',
			[
				'type'            => 'button',
				'class'           => 'btn-close',
				'data-bs-dismiss' => 'offcanvas',
				'aria-label'      => 'Schließen'
			] ) );		
		$m->closeBlock();

		// navbar entries
		$m->openBlock( 'nav', 'offcanvas-body pe-3', false, $this->getNavbarID( Navbar::NAV_LEFT, 'entries' ) );
		$m->openBlock( 'ul', 'nav nav-pills flex-column' );
		$m->addHTML( $this->buildNavbarEntries( Navbar::NAV_LEFT, '', 'collapse') );
		$m->closeBlock(2);
		
		$m->openBlock( 'div', false, false, $this->getNavbarID( Navbar::NAV_LEFT, 'end' ) );
		// menu toggles
		$menuList = $this->context
			->getMenuHandler()
			->getMenusByTogglePosition( Document\Menu::TOGGLE_NAV_LEFT );
		foreach ( $menuList as $menu ) {
			$m->addHTML( $menu->getToggle() );
		}
		$m->closeBlock();
		
		// close nav container and aside element
		$m->closeBlock(2);

		// return content
		return $m->output();
	}	
	
	/**
	 * getNavbarHtml()
	 * returns the navbar html
	 *
	 * @param position navbar position
	 */
	public function getNavbarHtml( $position ) {
		
		// undefined, don't return anything
		if ( !isset( $this->navbars[ $position ] ) ) {
			return '';
		}
		
		if ( $position === Navbar::NAV_TOP ) {
			// top navbar
			return $this->getNavTopHtml();
		} else {
			// left navbar
			return $this->getNavLeftHtml();
		}
		
	}
	
	/**
	 * getNavbarLeftToggle()
	 * returns the toggle html for the left navbar
	 */		
	public function getNavbarLeftToggle() {
		if ( $this->getNavbar( Navbar::NAV_LEFT ) === false ) {
			return;
		}
		
		// html
		$m = new Html\Html();
		
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