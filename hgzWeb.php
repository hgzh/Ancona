<?php
/**
 * ##### general.php #####
 * hgzWeb: Hauptdatei des Frameworks
 *
 * (C) 2015-2023 Hgzh
 *
 */

namespace hgzWeb;

require_once('config.php');    // Benutzerkonfiguration
require_once('html.php');      // HTML
require_once('form.php');      // Formulare
require_once('database.php');  // Datenbanken
require_once('exception.php'); // Exceptions
require_once('navbar.php');    // Navigationsleiste

use hgzWeb\ConfigService as Config;
use hgzWeb\HtmlService\Html as Html;
use hgzWeb\DocumentService as Document;

/**
 * ##### CLASS hgzWeb CLASS #####
 * Bootstrap-Dokument
 */
class hgzWeb {

	// Version
	public const VERSION = '1.02.220328';
	
	// Skripte und Links
	public $script = [];
	public $style  = [];

	// Flags
	private $flags = [];

	// Bereiche des HTML-Elements
	private $head = '';
	private $body = '';
	private $foot = '';
	
	// Navigationsleiste
	private $navTop = '';

	// Systemnachricht
	protected $message = '';
	
	// angezeigter Seiteninhalt
	protected static $content = '';
	
	/**
     * Klassenkonstruktor
	 * Initialisierungen
	 *
	 * Parameter:
	 * - title : optionaler Seitentitel
	 */
	public function __construct( $title = '' ) {
		// Seitentitel
		$this->flags['title'] = $title;

		// eigenes CSS oder JS vorhanden?
		if ( file_exists( 'addcss.css' ) ) {
			$this->flags['custom-css'] = true;
		}
		if ( file_exists( 'addjs.js' ) ) {
			$this->flags['custom-js'] = true;
		}
	}

	/**
	 * getFooterText()
	 * Darstellung des Texts im Fußbereich (überladbar, standardmäßig leer)
	 */
	public function getFooterText() {
		return '';
	}
	
	/**
	 * getNotificationBadge()
	 * Darstellung der Benachrichtigungs-Badge (überladbar, standardmäßig leer)
	 */
	public function getNotificationBadge() {
		return '';
	}

	/**
	 * getMessage()
	 * Darstellungen der Systemnachrichten (überladbar, standardmäßig leer)
	 */
	public function getMessage() {
		return '';
	}
	
	/**
	 * getCustomResourceLoad()
	 * Nachladen zusätzlicher externer Ressourcen (überladbar, standardmäßig leer)
	 */
	public function getCustomResourceLoad() {
		return false;
	}

	/**
	 * setNavTop()
	 * Navigationsleiste am oberen Seitenrand mit Logo setzen
	 *
	 * Parameter
	 * - navbar     : (verschachteltes) Array der Form 'Anzeigetext' => 'URL'
	 * - activeItem : Array-Key des aktiven Navigationseintrags
	 * - logout		: Logout-Form anzeigen
	 * - login		: Login-Form anzeigen
	 */
	public function setNavTop( Document\Navbar $navbar, $activeItem = '', $logout = false, $login = false ) {			
		
		// Navigationsleisten-Struktur beziehen
		$nav = $navbar->getStructure();
		
		// Klassen für Navigationsleiste bestimmen
		$navClass = '';

		// Fixiert am oberen Bildschirmrand?
		if ( Config\framework::get( 'navbar-sticky' ) === true ) {
			$navClass .= 'sticky-top ';	
		}

		// dunkles Farbschema?
		if ( Config\framework::get( 'navbar-dark' ) === true ) {
			$navClass .= 'navbar-dark ';	
		} else {
			$navClass .= 'navbar-light ';	
		}

		// neues HTML-Element
		$m = new Html();

		// Nav-Element
		$m->addHTML( '<nav id="hgzweb-navbar" class="navbar navbar-expand-lg hgzweb-owncolor ' . $navClass . '"' );
		if ( Config\framework::get( 'navbar-color' ) !== false ) { 
			$m->addHTML( ' style="background-color:' . Config\framework::get( 'navbar-color' ) . '"' );
		}
		$m->addHTML( '>' );

		// Container und eigentliche Einträge in der Navigationsleiste
		$m->openContainer();

		// Logo
		$m->addLink( hgzweb::getAbs(), 
					 '<img src="'
					   . hgzWeb::getAbs()
					   . '_images/' . Config\framework::get( 'image-logo' )
					   . '" height="35"/>',
					 'navbar-brand' );

		// Aus-/Einklappschalter für mobile Ansichten
		$m->addHTML( Html::elem( 'button',
							     [ 'type'           => 'button',
								   'class'          => 'navbar-toggler',
								   'data-bs-toggle' => 'collapse',
								   'data-bs-target' => '#menu-toggle',
								   'aria-controls'  => 'menu-toggle',
								   'aria-expanded'  => 'false',
								   'aria-label'     => 'Menü ausklappen'
								 ],
							     Html::elem( 'span',
										     [ 'class' => 'navbar-toggler-icon' ] 
										   )
							  ) );

		// Navigationsleisten-Einträge
		$m->openBlock( 'div', 'collapse navbar-collapse', '', 'menu-toggle' );
		$m->openBlock( 'ul', 'navbar-nav flex-grow-1' );

		// Einträge anzeigen
		$i = 0;
		foreach ( $nav as $k1 => $v1 ) {
			if ( $k1 == '!account' ) {
				continue;
			}
			
			if ( is_array( $v1 ) == true ) {
				// verschachtelt, Dropdown-Menü öffnen
				$m->openBlock( 'li', 'nav-item dropdown' );
				$m->addHTML( Html::elem( 'a',
									     [ 'class'          => 'nav-link dropdown-toggle',
										   'href'           => '#',
										   'id'             => 'navbar-dropdown-' . $i,
										   'role'           => 'button',
										   'data-bs-toggle' => 'dropdown',
										   'aria-haspopup'  => 'true',
										   'aria-expanded'  => 'false'
										 ],
									     $k1)
						   );

				// Einträge im Dropdown-Menü
				$m->openBlock( 'div', 'dropdown-menu' );
				foreach ( $v1 as $k2 => $v2 ) {
					if ( substr( $k2, 0, 8 ) === '!divider') {
						$m->addInline( 'div', '', 'dropdown-divider' );
					} else {
						$m->addLink( $v2, $k2, 'dropdown-item' );
					}
				}
				$m->closeBlock( 2 );
				$i++;
			} else {
				// einfaches Element
				if ( $activeItem == $k1 ) {
					$m->addInline( 'li', '<a class="nav-link" href="' . $v1 . '">' . $k1 . '</a>', 'nav-item active' );
				} else {
					$m->addInline( 'li', '<a class="nav-link" href="' . $v1 . '">' . $k1 . '</a>', 'nav-item' );
				}
			}
		}
		// Einträge schließen
		$m->closeBlock();
		
		// Benachrichtigungs-Badge
		$m->addHTML($this->getNotificationBadge());
		
		// Konto-Bereich
		if ( isset( $nav['!account'] ) ) {
			$m->openBlock( 'div', 'dropdown' );
			$m->addHTML( Html::elem( 'button',
								     [ 'class'          => 'btn btn-sm btn-secondary dropdown-toggle',
									   'type'           => 'button',
									   'data-bs-toggle' => 'dropdown'
									 ],
								     $nav['!account']['label'])
					   );
			$m->openBlock( 'div', 'dropdown-menu' );
			foreach ( $nav['!account']['*'] as $k1 => $v1 ) {
				if ( substr( $k1, 0, 8 ) === '!divider' ) {
					$m->addInline( 'div', '', 'dropdown-divider' );
				} else {
					$m->addLink( $v1, $k1, 'dropdown-item' );
				}
			}
			
			if ( $logout === true ) {
				$m->addInline( 'div', '', 'dropdown-divider' );
				$m->addHTML( '<form class="form-inline px-4 py-1" action="'
							   . hgzWeb::getAbs()
							   . Config\framework::get( 'auth-url' )
							   . '" method="POST">'
						   );
				$m->addHTML( '<input type="hidden" name="lgtype" value="logout" />' );
				$m->addHTML( '<input type="hidden" name="lgfrom" value="interface" />' );
				$m->addHTML( '<button class="btn btn-outline-danger btn-sm my-2 my-sm-0" type="submit">'
							   . Config\message::get( 'logout' )
							   . '</button>');
				$m->addHTML( '</form>' );
				$m->closeBlock( 2 );
			}
		}
		
		// Login-Maske anzeigen
		if ( $login === true ) {
			$m->openBlock( 'div', 'dropdown' );
			$m->addHTML( Html::elem( 'button',
								     [ 'class'              => 'btn btn-sm btn-primary dropdown-toggle',
									   'type'               => 'button',
									   'data-bs-toggle'     => 'dropdown',
								       'data-bs-auto-close' => 'outside'
									 ],
								     '<i class="fas fa-sign-in-alt"></i> '
									    . Config\message::get( 'login' ))
					   );
			$m->openBlock( 'div', 'dropdown-menu' );
			$m->addHTML( '<form class="px-3 py-2" action="'
						   . hgzWeb::getAbs()
						   . Config\framework::get( 'auth-url' )
						   . '" method="POST">' );
			$m->addHTML( '<input class="form-control form-control-sm" type="text" name="lgname" placeholder="'
						   . Config\message::get( 'username' ) . '" required />' );
			$m->addHTML( '<input class="form-control form-control-sm mt-2" type="password" name="lgpasswort" placeholder="'
						   . Config\message::get( 'password' ) . '" required />' );
			$m->addHTML( '<input type="hidden" name="lgtype" value="login" />' );
			$m->addHTML( '<input type="hidden" name="lgfrom" value="interface" />' );
			$m->addHTML( '<button class="btn btn-outline-primary btn-sm mt-2" type="submit">'
						   . Config\message::get( 'login' )
						   . '</button>' );
			$m->addHTML( '</form>' );
			$m->closeBlock( 2 );
		}

		// Logout-Maske anzeigen
		if ( $logout === true && !isset( $nav['!account'] ) ) {
			$m->addHTML( '<form class="form-inline my-2 my-lg-0" action="'
						   . hgzWeb::getAbs()
						   . Config\framework::get( 'auth-url' )
						   . '" method="POST">');
			$m->addHTML( '<input type="hidden" name="lgtype" value="logout" />' );
			$m->addHTML( '<input type="hidden" name="lgfrom" value="interface" />' );
			$m->addHTML( '<button class="btn btn-outline-danger btn-sm my-2 my-sm-0" type="submit">'
						   . Config\message::get( 'logout' )
						   . '</button>');
			$m->addHTML( '</form>' );
		}

		// Container mit den Einträgen und für Logo und Einträge schließen
		$m->closeContainer();
		$m->closeBlock();
		
		// Navbar schließen
		$m->addHTML( '</nav>' );

		// Inhalt ausgeben
		$this->navTop = $m->output();
	}

	/**
	 * setContent()
	 * benutzerdefinierten Inhaltsbereich setzen
	 *
	 * Parameter
	 * - content : Seiteninhalt
	 */
	public function setContent( $content ) {
		self::$content .= $content;
	}

	/**
	 * addHTML()
	 * beliebigen HTML-Code an der aktuellen Position des Inhalts einfügen
	 *
	 * Parameter
	 * - code : einzufügender Code
	 */
	public function addHTML( $code ) {
		self::$content .= $code;
	}

	/**
	 * loadJS()
	 * JavaScript-Resource laden
	 *
	 * Parameter
	 * - src         : Quelle
	 * - integrity   : Hash
	 * - crossorigin : Cross-Origin
	 */
	public function loadJS( $src, $integrity = false, $crossorigin = 'anonymous' ) {
		$load = [];
		$load['src']         = $src;
		$load['integrity']   = $integrity;
		$load['crossorigin'] = $crossorigin;
		
		$this->script[] = $load;
	}

	/**
	 * loadCSS()
	 * CSS-Resource laden
	 *
	 * Parameter
	 * - href        : Quelle
	 * - integrity   : Hash
	 * - crossorigin : Cross-Origin
	 */
	public function loadCSS( $href, $integrity = false, $crossorigin = 'anonymous' ) {
		$load = [];
		$load['href']        = $href;
		$load['integrity']   = $integrity;
		$load['crossorigin'] = $crossorigin;

		$this->style[] = $load;
	}
	
	/**
	 * buildResourceLoader()
	 * Standard- und benutzerdefinierte Ressourcen laden
	 */
	private function buildResourceLoader() {
		// CSS: Bootstrap
		$this->loadCSS( 'https://cdn.jsdelivr.net/npm/bootstrap-dark-5@1.1.3/dist/css/bootstrap-dark.min.css',
					    'sha384-pZAJcuaxKZEGkzXV5bYqUcSwBfMZPdQS/+JXdYOu9ScyZJMnGHD5Xi6HVHfZuULH' );
				
		// JS: jQuery
		$this->loadJS( 'https://code.jquery.com/jquery-3.5.1.slim.min.js',
					   'sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj' );
		
		// JS: Popper
		$this->loadJS( 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js',
					   'sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU' );

		// JS: Bootstrap
		$this->loadJS( 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js',
					   'sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p' );
		
		// benutzerdefinierte Resourcen laden
		$this->getCustomResourceLoad();
			
		// Autoloader für eigene JS- und CSS-Dateien
		if ( $this->flags['custom-js'] === true ) {
			$this->loadJS( hgzWeb::getAbs() . 'addjs.js');
		}
		if ( $this->flags['custom-css'] === true ) {
			$this->loadCSS( hgzWeb::getAbs() . 'addcss.css');
		}
	}

	/**
	 * buildHead()
	 * Kopfbereich des HTML-Dokuments zusammenstellen
	 */
	private function buildHead() {
		// Kopfbereich
		$headHtml = '';

		// UTF8-Kodierung
		$headHtml .= Html::elem( 'meta',
								 [ 'charset' => 'utf-8' ],
								 '',
								 false );

		// Kompatibilitätsmodus für IE anfordern
		$headHtml .= Html::elem( 'meta',
								 [ 'http-equiv' => 'X-UA-Compatible',
								   'content'    => 'IE=edge'
								 ],
								 '',
								 false );

		// Viewport-Einstellungen
		$headHtml .= Html::elem( 'meta',
								 [ 'name'    => 'viewport',
								   'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no'
								 ],
								 '',
								 false );
		
		// Color-Scheme
		$headHtml .= Html::elem( 'meta',
								 [ 'name'    => 'color-scheme',
								   'content' => 'light dark'
								 ],
								 '',
								 false );

		// Indizierung durch Bots erlauben oder verbieten
		if ( Config\framework::get( 'bots-allow' ) === false ) {
			$headHtml .= Html::elem( 'meta',
									 [ 'name'    => 'robots',
									   'content' => 'noindex, nofollow'
									 ],
									 '',
									 false );
		}
		
		// Stylesheets
		foreach ( $this->style as $v1 ) {
			$headHtml .= Html::elem( 'link',
									 [ 'rel'         => 'stylesheet',
									   'href'        => $v1['href'],
									   'integrity'   => $v1['integrity'],
									   'crossorigin' => $v1['crossorigin']
									 ],
									 '',
									 false );
		}
		
		// Favicon
		$headHtml .= Html::elem( 'link',
								 [ 'rel'         => 'shortcut icon',
								   'href'        => hgzWeb::getAbs()
								                    . '_images/' . Config\framework::get( 'image-favicon' )
								 ],
								 '',
								 false);

		// Seitentitel
		$headHtml .= Html::elem( 'title',
								 [],
								 Config\framework::get( 'title' )
								   . $this->flags['title']);

		// Kopfbereich abschließen
		$this->head .= Html::elem( 'head',
								   [ 'lang' => 'de' ],
								   $headHtml );		
	}
	
	/**
	 * buildFoot()
	 * Fußbereich des HTML-Dokuments zusammenstellen
	 */
	private function buildFoot() {
		// Skripte
		foreach ( $this->script as $v1 ) {
			$this->foot .= Html::elem( 'script',
									   [ 'src'         => $v1['src'],
									     'integrity'   => $v1['integrity'],
									     'crossorigin' => $v1['crossorigin']
									   ]);
		}
	}
	
	/**
	 * buildBody()
	 * Inhaltsbereich des HTML-Dokuments zusammenstellen
	 */
	private function buildBody() {
		// Html
		$m = new Html();
		
		// Navigationsleiste oben
		$m->addHTML( $this->navTop );
		
		// Container mit Y-Abstand
		$m->openContainer( false, 'mt-3', 'hgzweb-content' );
		
		// Systemnachricht
		$this->getMessage();
		$m->addHTML( $this->message );
		
		// benutzerdefinierter Seiteninhalt
		$m->addHTML( self::$content );
		
		// Fußbereich
		$m->addHTML( Html::elem( 'div',
							     [ 'class' => 'row bg-light mt-4 pt-3 hgzweb-owncolor',
								   'id'    => 'hgzweb-footer'
								 ],
							     $this->getFooterText())
				   );
		
		// Container schließen
		$m->closeContainer();
		
		// Body setzen
		$this->body = $m->output();
	}
	
	/**
	 * output()
	 * Ausgabe des HTML-Dokuments
	 */
	public function output() {
		// Ressourcen laden
		$this->buildResourceLoader();
		
		// Dokument laden
		$this->buildHead();
		$this->buildBody();
		$this->buildFoot();
		
		// Html erzeugen
		$d = new Html(); // Dokument
		$h = new Html(); // <html>
		$b = new Html(); // <body>
		
		// Dokument beginnen
		$d->addHTML( '<!DOCTYPE html>' );
		
		// <head>
		// Kopfbereich
		$d->addHTML( $this->head );
		
		// <body>
		// Inhaltsbereich
		$b->addHTML( $this->body );

		// Fußbereich
		$b->addHTML( $this->foot );
		
		// <body> in <html>
		$h->addHTML( Html::elem( 'body',
							     [ 'role' => 'document' ],
							     $b->output())
				   );
		
		// <html> in Dokument
		$d->addHTML( Html::elem( 'html',
							     [ 'lang' => 'de' ],
							     $h->output())
				   );

		
		// Dokument ausgeben
		echo $d->output();
	}
	
	/**
	 * getAbs()
	 * absoluten Pfad der Website ausgeben
	 */
	public static function getAbs() {
		return 'https://' . Config\framework::get( 'host' ) . '/' . Config\framework::get( 'sub-path' );
	}

	/**
	 * sortArray()
	 * Array sortieren, vgl. array_multisort()
	 */		
	public static function sortArray() {
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
	 * Zeichenkette für Einsatz in URL vorbereiten
	 * 
	 * Parameter:
	 * - $input  :  Eingabetext
	 * - $maxlen : Maximale Länge der Rückgabe
	 */		
	public static function encodeForURL( $input, $maxlen = 40 ) {
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
