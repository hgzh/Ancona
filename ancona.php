<?php
/**
 *    _   _  _  ___ ___  _  _   _   
 *   /_\ | \| |/ __/ _ \| \| | /_\  
 *  / _ \| .` | (_| (_) | .` |/ _ \ 
 * /_/ \_\_|\_|\___\___/|_|\_/_/ \_\
 *                                 
 * Ancona is a web page framework based on Bootstrap (getbootstrap.com)
 * with integration of mysqli and other features.
 *
 * Released under the MIT license.
 * (C) 2015-2023 Hgzh
 *
 */

namespace Ancona;

use Ancona\ConfigService as Config;
use Ancona\HtmlService as Html;
use Ancona\DocumentService as Document;
use Ancona\DocumentHandler;

require_once( 'config.php' );

/**
 * Autoloader
 */	
spl_autoload_register( function ( $classname ) {
	$classname = str_replace( 'Ancona\\', '', $classname );
	$classname = str_replace('\\', '/', $classname);
	if ( file_exists( __DIR__ . '/' . $classname . '.php' ) ) {
    	include __DIR__ . '/' . $classname . '.php';
	}
} );

/**
 * == ANCONA CLASS ==
 * base class for the framework
 */	
class Ancona {

	// version
	public const VERSION = '2.02.230509';
	
	// flags
	private $flags = [];

	// html document areas
	private $head = '';
	private $body = '';
	private $foot = '';
	
	// handlers for specific parts of the document
	protected $menuHandler;
	protected $navbarHandler;
	protected $resourceHandler;
	protected $sidebarHandler;
	protected $themeHandler;
	
	// system message
	protected $message = '';
	
	// page content
	protected static $content = '';
	
	/**
     * __construct()
	 * initializations
	 *
	 * @param title optional page title
	 */
	public function __construct( $title = '' ) {
		// page title
		$this->flags['title'] = $title;

		// theme handler
		$this->themeHandler = new DocumentHandler\Theme();			
		
		// menu handler
		$this->menuHandler = new DocumentHandler\Menu( $this );
		
		// navbar handler
		$this->navbarHandler = new DocumentHandler\Navbar( $this );
		
		// resource handler
		$this->resourceHandler = new DocumentHandler\Resource();

		// sidebar handler
		$this->sidebarHandler = new DocumentHandler\Sidebar();	
		
	}

	/**
	 * getSidebars()
	 * allow customization of the sidebar (overload)
	 */
	public function getSidebars() {
		return false;
	}
	
	/**
	 * getNotificationBadge()
	 * allow customization of the notification badge (overload)
	 */
	public function getNotificationBadge() {
		return '';
	}

	/**
	 * getMessage()
	 * allow customization of how system messages appear (overload)
	 */
	public function getMessage() {
		return '';
	}
	
	/**
	 * getCustomResourceLoad()
	 * allow loading of custom foreign resources (overload)
	 */
	public function getCustomResourceLoad() {
		return false;
	}
	
	/**
	 * getCustomMenus()
	 * allow customization of the menus (overload)
	 */
	public function getCustomMenus() {
		return false;
	}
	
	/**
	 * getMenuHandler()
	 * access to the menu handler
	 */	
	public function getMenuHandler() : DocumentHandler\Menu {
		return $this->menuHandler;	
	}
	
	/**
	 * getNavbarHandler()
	 * access to the navbar handler
	 */	
	public function getNavbarHandler() : DocumentHandler\Navbar {
		return $this->navbarHandler;	
	}
	
	/**
	 * getResourceHandler()
	 * access to the resource handler
	 */		
	public function getResourceHandler() : DocumentHandler\Resource {
		return $this->resourceHandler;	
	}		
	
	/**
	 * getSidebarHandler()
	 * access to the sidebar handler
	 */		
	public function getSidebarHandler() : DocumentHandler\Sidebar {
		return $this->sidebarHandler;	
	}	
	
	/**
	 * getThemeHandler()
	 * access to the theme handler
	 */		
	public function getThemeHandler() : DocumentHandler\Theme {
		return $this->themeHandler;	
	}		
	
	/**
	 * setContent()
	 * set content area
	 *
	 * @param content new page content
	 */
	public function setContent( $content ) {
		self::$content .= $content;
	}
	
	/**
	 * buildResourceLoader()
	 * load standard and custom resources
	 */
	private function buildResourceLoader() {
		// JS: ThemeToggle
		if ( Config\framework::get( 'themes', false ) !== false ) {
			$this->resourceHandler->createResource(
				'anc-themetoggle',
				Document\Resource::TYPE_JS,
				Config\framework::get( 'ancona-path' ) . 'js/themeToggle.js'
			);
		}
		
		// CSS: layout
		$this->resourceHandler->createResource(
			'anc-layout',
			Document\Resource::TYPE_CSS,
			Config\framework::get( 'ancona-path' ) . 'css/layout.css'
		);
		
		// CSS: theme fixes
		$this->resourceHandler->createResource(
			'anc-theme-fixes',
			Document\Resource::TYPE_CSS,
			Config\framework::get( 'ancona-path' ) . 'css/theme-fixes.css'
		);
		
		// CSS: bootstrap
		$this->resourceHandler->createResource(
			'anc-bootstrap-css',
			Document\Resource::TYPE_CSS,
			'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
			'sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN'
		);
		
		// CSS: bootstrap icons
		$this->resourceHandler->createResource(
			'anc-bootstrap-icons',
			Document\Resource::TYPE_CSS,
			'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css',
			'sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e'
		);
					   
		// JS: jQuery
		$this->resourceHandler->createResource(
			'anc-jquery',
			Document\Resource::TYPE_JS,
			'https://code.jquery.com/jquery-3.5.1.slim.min.js',
			'sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj'
		);
		
		// JS: popper
		$this->resourceHandler->createResource(
			'anc-popper',
			Document\Resource::TYPE_JS,
			'https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js',
			'sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3'
		);

		// JS: bootstrap
		$this->resourceHandler->createResource(
			'anc-bootstrap-js',
			Document\Resource::TYPE_JS,
			'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js',
			'sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+'
		);

		// JS: moduleLoader
		$this->resourceHandler->createResource(
			'anc-moduleloader',
			Document\Resource::TYPE_JS,
			Config\framework::get( 'ancona-path' ) . 'js/moduleLoader.js'
		);				
		
		// get custom resources
		$this->getCustomResourceLoad();
	}

	/**
	 * buildHead()
	 * create head of document
	 */
	private function buildHead() {
		// head html
		$headHtml = '';

		// utf8 encoding
		$headHtml .= Html\Html::elem(
			'meta',
			[ 'charset' => 'utf-8' ],
			'',
			false
		);

		// request compatibility mode for IE
		$headHtml .= Html\Html::elem(
			'meta',
			[
				'http-equiv' => 'X-UA-Compatible',
				'content'    => 'IE=edge'
			],
			'',
			false
		);

		// viewport settings
		$headHtml .= Html\Html::elem(
			'meta',
			[
				'name'    => 'viewport',
				'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no'
			],
			'',
			false
		);
		
		// color scheme
		$headHtml .= Html\Html::elem(
			'meta',
			[
				'name'    => 'color-scheme',
				'content' => 'light dark'
			],
			'',
			false
		);

		// allow robots
		if ( Config\framework::get( 'bots-allow' ) === false ) {
			$headHtml .= Html\Html::elem(
				'meta',
				[
					'name'    => 'robots',
					'content' => 'noindex, nofollow'
				],
				'',
				false
			);
		}
		
		// stylesheets
		$headHtml .= $this->resourceHandler->getResourcesHtmlByType( Document\Resource::TYPE_CSS );
		
		// favicon
		$headHtml .= Html\Html::elem(
			'link',
			[
				'rel'  => 'shortcut icon',
				'href' => Ancona::getAbs()
					. '_images/' . Config\framework::get( 'image-favicon' )
			],
			'',
			false
		);

		// page title
		$headHtml .= Html\Html::elem(
			'title',
			[],
			Config\framework::get( 'title' )
				. $this->flags['title']
		);

		// enclose in head element
		$this->head .= Html\Html::elem(
			'head',
			[ 'lang' => 'de' ],
			$headHtml
		);		
	}
	
	/**
	 * buildFoot()
	 * create foot of document
	 */
	private function buildFoot() {
		// scripts
		$this->foot .= $this->resourceHandler->getResourcesHtmlByType( Document\Resource::TYPE_JS );
	}
	
	/**
	 * buildFooter()
	 * create footer area of document
	 */
	private function buildFooter() : string {
		$m = new Html\Html();
		
		if ( $this->sidebarHandler->getSidebar( DocumentHandler\Sidebar::SIDEBAR_FOOTER ) !== false ) {
			$m->openBlock(
				'footer',
				'bg-body-tertiary mt-3',
				false,
				'anc-footer'
			);
			$m->addInline(
				'div',
				$this->sidebarHandler->getSidebarHtml( DocumentHandler\Sidebar::SIDEBAR_FOOTER ),
				'container py-4'
			);
			$m->closeBlock();
		}
		
		// return html
		return $m->output();
	}

	/**
	 * buildFooter()
	 * create content area of document
	 */
	private function buildContent() : string {
		$m = new Html\Html();
		
		// check if left navbar exists and add a class to anc-body for css
		$navLeft = '';
		if ( $this->navbarHandler->getNavbar( DocumentHandler\Navbar::NAV_LEFT ) !== false ) {
			$navLeft = ' anc-nav-left-exists';
		}
		
		// body container
		$m->openContainer(false, 'mt-3' . $navLeft, 'anc-body' );

		// include left navbar
		$m->addHTML(
			$this->navbarHandler->getNavbarHtml( DocumentHandler\Navbar::NAV_LEFT )
		);		
		
		// main content element
		$m->openBlock( 'main', false, false, 'anc-content' );

		// system messages
		if ( $this->message !== '' ) {
			$m->openBlock( 'div', false, false, 'anc-content-message' );
			$m->addHTML( $this->message );
			$m->closeBlock();
		}
		
		// main content
		$m->openBlock( 'div', false, false, 'anc-content-main' );
		
		// toggle for the left navbar if viewport is too thin
		$m->addHTML( $this->navbarHandler->getNavbarLeftToggle() );
		
		// add content
		$m->addHTML( self::$content );
		
		// right sidebar
		if ( $this->sidebarHandler->getSidebar( DocumentHandler\Sidebar::SIDEBAR_RIGHT ) !== false ) {
			$m->openBlock( 'div', false, false, 'anc-content-sidebar' );
			$m->addHTML(
				$this->sidebarHandler->getSidebarHtml( DocumentHandler\Sidebar::SIDEBAR_RIGHT )
			);
			$m->closeBlock();
		}
		
		// close content divs
		$m->closeBlock( 2 );
		
		// finish main container
		$m->closeContainer();
		
		// return html
		return $m->output();
	}
	
	/**
	 * buildBody()
	 * create body area of document
	 */
	private function buildBody() {
		// html
		$m = new Html\Html();
		
		// get customizations
		$this->getMessage();
		$this->getSidebars();

		// menus
		$m->addHTML( $this->menuHandler->getMenusHtml() );		
		
		// top navbar
		$m->addHTML(
			$this->navbarHandler->getNavbarHtml( DocumentHandler\Navbar::NAV_TOP )
		);
		
		// content
		$m->addHTML(
			$this->buildContent()
		);
		
		// footer
		$m->addHTML(
			$this->buildFooter()
		);
		
		// set body
		$this->body = $m->output();
	}
	
	/**
	 * output()
	 * print document
	 */
	public function output() {
		// load resources
		$this->buildResourceLoader();
		
		// load document
		$this->buildHead();
		$this->buildBody();
		$this->buildFoot();
		
		// create html objects
		$d = new Html\Html(); // document
		$h = new Html\Html(); // <html>
		$b = new Html\Html(); // <body>
		
		// start document
		$d->addHTML( '<!DOCTYPE html>' );
		
		// <head>
		// header
		$d->addHTML( $this->head );
		
		// <body>
		// content
		$b->addHTML( $this->body );

		// footer
		$b->addHTML( $this->foot );
		
		// <body> in <html>
		$h->addHTML( Html\Html::elem(
			'body',
			[ 'role' => 'document' ],
			$b->output()
		));
		
		// <html> in document
		$d->addHTML( Html\Html::elem(
			'html',
			[ 'lang' => 'de' ],
			$h->output()
		));

		// output document
		echo $d->output();
	}
	
	/**
	 * getAbs()
	 * return absolute path of the website
	 */
	public static function getAbs() : string {
		return 'https://' . Config\framework::get( 'host' ) . '/' . Config\framework::get( 'sub-path' );
	}

	/**
	 * sortArray()
	 * sort array, see php's array_multisort()
	 */		
	public static function sortArrayx() : array {
		$args = func_get_args();
		$data = array_shift( $args );
		foreach ( $args as $n => $field ) {
			if ( is_string( $field ) ) {
				$tmp = [];
				foreach ( $data as $key => $row ) {
					$tmp[$key] = $row[$field];
				}
				$args[$n] = $tmp;
			}
		}
		$args[] = &$data;
		call_user_func_array( 'array_multisort', $args );
		return array_pop( $args );
	}
	
	/**
	 * encodeForURL()
	 * prepare string for usage in url
	 * 
	 * @param input input value
	 * @param maxlen maximum length of encoded value
	 */		
	public static function encodeForURL( $input, $maxlen = 40 ) : string {
		$input   = str_replace( ['Ä', 'Ö', 'Ü'],
							    ['ä', 'ö', 'ü'],
							    $input);
		$search  = ['/[\.\/\\#"„“”‚‘,@:\?=\%\<\>\}\{\[\]\^]/', '/\s/', '/ä/', '/ö/', '/ü/', '/ß/'];
		$escaped = preg_replace( $search, ['', '-', 'a', 'o', 'u', 'ss'], $input );
		$escaped = strtolower( $escaped );
		
		if ( $maxlen != false ) {
			$escaped = substr( $escaped, 0, $maxlen );
		}
		return $escaped;
	}
}

?>
