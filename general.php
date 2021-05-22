<?php
/**
 * ##### general.php #####
 * hgzWeb: Dokumentenerstellung
 *
 * (C) 2015-2021 Hgzh
 *
 */

require_once('user_config.php'); // Benutzerkonfiguration
require_once('html.php');        // HTML
require_once('form.php');        // Formulare
require_once('database.php');    // Datenbanken

/**
 * ##### CLASS hgzWeb CLASS #####
 * Bootstrap-Dokument
 */
class hgzWeb {

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
	private $navigation = '';

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
	public function __construct($title = '') {
		// Seitentitel
		$this->flags['title'] = $title;

		// eigenes CSS oder JS vorhanden?
		if (file_exists('addcss.css')) {
			$this->flags['custom-css'] = true;
		}
		if (file_exists('addjs.js')) {
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
	 * setNavigation()
	 * Navigationsleiste mit Logo setzen
	 *
	 * Parameter
	 * - navInfo    : (verschachteltes) Array der Form 'Anzeigetext' => 'URL'
	 * - activeItem : Array-Key des aktiven Navigationseintrags
	 * - logout		: Logout-Form anzeigen
	 * - login		: Login-Form anzeigen
	 */
	public function setNavigation(array $navInfo, $activeItem = '', $logout = false, $login = false) {			

		// Klassen für Navigationsleiste bestimmen
		$navClass = '';

		// Fixiert am oberen Bildschirmrand?
		if (UserConfig::$var['navbar-sticky'] === true) {
			$navClass .= 'sticky-top ';	
		}

		// dunkles Farbschema?
		if (UserConfig::$var['navbar-dark'] === true) {
			$navClass .= 'navbar-dark ';	
		} else {
			$navClass .= 'navbar-light ';	
		}

		// neues HTML-Element
		$m = new Html();

		// Nav-Element
		$m->addHTML('<nav id="hgzweb-navbar" class="navbar navbar-expand-lg ' . $navClass . '"');
		if (UserConfig::$var['navbar-color'] !== false) { 
			$m->addHTML(' style="background-color:' . UserConfig::$var['navbar-color'] . '"');
		}
		$m->addHTML('>');

		// Container und eigentliche Einträge in der Navigationsleiste
		$m->openContainer();

		// Logo
		$m->addLink(hgzweb::getAbs(), '<img src="https://' . UserConfig::$var['host'] . '/' . UserConfig::$var['sub-path'] . '_images/' . UserConfig::$var['image-logo'] . '" height="35"/>', 'navbar-brand');

		// Aus-/Einklappschalter für mobile Ansichten
		$m->addHTML(Html::elem('button',
							   ['type'           => 'button',
								'class'          => 'navbar-toggler',
								'data-bs-toggle' => 'collapse',
								'data-bs-target' => '#menu-toggle',
								'aria-controls'  => 'menu-toggle',
								'aria-expanded'  => 'false',
								'aria-label'     => 'Menü ausklappen'],
							   Html::elem('span',
										  ['class' => 'navbar-toggler-icon'])
							  ));

		// Navigationsleisten-Einträge
		$m->openBlock('div', 'collapse navbar-collapse', '', 'menu-toggle');
		$m->openBlock('ul', 'navbar-nav flex-grow-1');

		// Einträge anzeigen
		$i = 0;
		foreach ($navInfo as $k1 => $v1) {
			if ($k1 == '!account') {
				continue;
			}
			
			if (is_array($v1) == true) {
				// verschachtelt, Dropdown-Menü öffnen
				$m->openBlock('li', 'nav-item dropdown');
				$m->addHTML(Html::elem('a',
									   ['class'          => 'nav-link dropdown-toggle',
										'href'           => '#',
										'id'             => 'navbar-dropdown-' . $i,
										'role'           => 'button',
										'data-bs-toggle' => 'dropdown',
										'aria-haspopup'  => 'true',
										'aria-expanded'  => 'false'],
									   $k1));

				// Einträge im Dropdown-Menü
				$m->openBlock('div', 'dropdown-menu');
				foreach ($v1 as $k2 => $v2) {
					if (substr($k2, 0, 8) === '!divider') {
						$m->addInline('div', '', 'dropdown-divider');
					} else {
						$m->addLink($v2, $k2, 'dropdown-item');
					}
				}
				$m->closeBlock(2);
				$i++;
			} else {
				// einfaches Element
				if ($activeItem == $k1) {
					$m->addInline('li', '<a class="nav-link" href="' . $v1 . '">' . $k1 . '</a>', 'nav-item active');
				} else {
					$m->addInline('li', '<a class="nav-link" href="' . $v1 . '">' . $k1 . '</a>', 'nav-item');
				}
			}
		}
		// Einträge schließen
		$m->closeBlock();
		
		// Benachrichtigungs-Badge
		$m->addHTML($this->getNotificationBadge());
		
		// Konto-Bereich
		if (isset($navInfo['!account'])) {
			$m->openBlock('div', 'dropdown');
			$m->addHTML(Html::elem('button',
								   ['class'          => 'btn btn-sm btn-secondary dropdown-toggle',
									'type'           => 'button',
									'data-bs-toggle' => 'dropdown'],
								   $navInfo['!account']['title']));
			$m->openBlock('div', 'dropdown-menu');
			foreach ($navInfo['!account']['*'] as $k1 => $v1) {
				if (substr($k1, 0, 8) === '!divider') {
					$m->addInline('div', '', 'dropdown-divider');
				} else {
					$m->addLink($v1, $k1, 'dropdown-item');
				}
			}
			
			if ($logout === true) {
				$m->addInline('div', '', 'dropdown-divider');
				$m->addHTML('<form class="form-inline px-4 py-1" action="https://' . UserConfig::$var['host'] . '/' . UserConfig::$var['sub-path'] . UserConfig::$var['auth-url'] . '" method="POST">');
				$m->addHTML('<input type="hidden" name="lgtype" value="logout" />');
				$m->addHTML('<input type="hidden" name="lgfrom" value="interface" />');
				$m->addHTML('<button class="btn btn-outline-danger btn-sm my-2 my-sm-0" type="submit">' . UserConfig::$txt['logout'] . '</button>');
				$m->addHTML('</form>');
				$m->closeBlock(2);
			}
		}
		
		// Login-Maske anzeigen
		if ($login === true) {
			$m->addHTML('<form class="d-flex flex-column flex-md-row my-1 my-md-0" action="https://' . UserConfig::$var['host'] . '/' . UserConfig::$var['sub-path'] . UserConfig::$var['auth-url'] . '" method="POST">');
			$m->addHTML('<input class="form-control form-control-sm me-0 me-md-1 mb-1 mb-md-0" type="text" name="lgname" placeholder="' . UserConfig::$txt['username'] . '" required />');
			$m->addHTML('<input class="form-control form-control-sm me-0 me-md-1 mb-1 mb-md-0" type="password" name="lgpasswort" placeholder="' . UserConfig::$txt['password'] . '" required />');
			$m->addHTML('<input type="hidden" name="lgtype" value="login" />');
			$m->addHTML('<input type="hidden" name="lgfrom" value="interface" />');
			$m->addHTML('<button class="btn btn-outline-primary btn-sm align-self-start" type="submit">' . UserConfig::$txt['login'] . '</button>');
			$m->addHTML('</form>');
		}

		// Logout-Maske anzeigen
		if ($logout === true && !isset($navInfo['!account'])) {
			$m->addHTML('<form class="form-inline my-2 my-lg-0" action="https://' . UserConfig::$var['host'] . '/' . UserConfig::$var['sub-path'] . UserConfig::$var['auth-url'] . '" method="POST">');
			$m->addHTML('<input type="hidden" name="lgtype" value="logout" />');
			$m->addHTML('<input type="hidden" name="lgfrom" value="interface" />');
			$m->addHTML('<button class="btn btn-outline-danger btn-sm my-2 my-sm-0" type="submit">' . UserConfig::$txt['logout'] . '</button>');
			$m->addHTML('</form>');
		}

		// Container mit den Einträgen und für Logo und Einträge schließen
		$m->closeContainer();
		$m->closeBlock();
		
		// Navbar schließen
		$m->addHTML('</nav>');

		// Inhalt ausgeben
		$this->navigation = $m->output();
	}

	/**
	 * setContent()
	 * benutzerdefinierten Inhaltsbereich setzen
	 *
	 * Parameter
	 * - content : Seiteninhalt
	 */
	public function setContent($content) {
		self::$content .= $content;
	}

	/**
	 * addHTML()
	 * beliebigen HTML-Code an der aktuellen Position des Inhalts einfügen
	 *
	 * Parameter
	 * - code : einzufügender Code
	 */
	public function addHTML($code) {
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
	public function loadJS($src, $integrity = false, $crossorigin = 'anonymous') {
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
	public function loadCSS($href, $integrity = false, $crossorigin = 'anonymous') {
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
		$this->loadCSS('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css',
					   'sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x');
				
		// JS: jQuery
		$this->loadJS('https://code.jquery.com/jquery-3.5.1.slim.min.js',
					  'sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj');
		
		// JS: Popper
		$this->loadJS('https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js',
					  'sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU');

		// JS: Bootstrap
		$this->loadJS('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js',
					  'sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4');
		
		// benutzerdefinierte Resourcen laden
		$this->getCustomResourceLoad();
			
		// Autoloader für eigene JS- und CSS-Dateien
		if ($this->flags['custom-js'] === true) {
			$this->loadJS('https://' . UserConfig::$var['host'] . '/' . UserConfig::$var['sub-path'] . 'addjs.js');
		}
		if ($this->flags['custom-css'] === true) {
			$this->loadCSS('https://' . UserConfig::$var['host'] . '/' . UserConfig::$var['sub-path'] . 'addcss.css');
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
		$headHtml .= Html::elem('meta',
								['charset' => 'utf-8'],
								'',
								false);

		// Kompatibilitätsmodus für IE anfordern
		$headHtml .= Html::elem('meta',
								['http-equiv' => 'X-UA-Compatible',
								 'content'    => 'IE=edge'],
								'',
								false);

		// Viewport-Einstellungen
		$headHtml .= Html::elem('meta',
								['name'    => 'viewport',
								 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no'],
								'',
								false);

		// Indizierung durch Bots erlauben oder verbieten
		if (UserConfig::$var['bots-allow'] === false) {
			$headHtml .= Html::elem('meta',
									['name'    => 'robots',
									 'content' => 'noindex, nofollow'],
									'',
									false);
		}
		
		// Stylesheets
		foreach ($this->style as $v1) {
			$headHtml .= Html::elem('link',
									['rel'         => 'stylesheet',
									 'href'        => $v1['href'],
									 'integrity'   => $v1['integrity'],
									 'crossorigin' => $v1['crossorigin']],
									'',
									false);
		}
		
		// Favicon
		$headHtml .= Html::elem('link',
								['rel'         => 'shortcut icon',
								 'href'        => 'https://' . UserConfig::$var['host'] . '/' . UserConfig::$var['sub-path'] . '_images/' . UserConfig::$var['image-favicon']],
								'',
								false);

		// Seitentitel
		$headHtml .= Html::elem('title',
								[],
								UserConfig::$var['title'] . $this->flags['title']);

		// Kopfbereich abschließen
		$this->head .= Html::elem('head',
								  ['lang' => 'de'],
								  $headHtml);		
	}
	
	/**
	 * buildFoot()
	 * Fußbereich des HTML-Dokuments zusammenstellen
	 */
	private function buildFoot() {
		// Skripte
		foreach ($this->script as $v1) {
			$this->foot .= Html::elem('script',
									  ['src'         => $v1['src'],
									   'integrity'   => $v1['integrity'],
									   'crossorigin' => $v1['crossorigin']]);
		}
	}
	
	/**
	 * buildBody()
	 * Inhaltsbereich des HTML-Dokuments zusammenstellen
	 */
	private function buildBody() {
		// Html
		$m = new Html();
		
		// Navigation
		$m->addHTML($this->navigation);
		
		// Container mit Y-Abstand
		$m->openContainer(false, 'mt-3', 'hgzweb-content');
		
		// Systemnachricht
		$this->getMessage();
		$m->addHTML($this->message);
		
		// benutzerdefinierter Seiteninhalt
		$m->addHTML(self::$content);
		
		// Fußbereich
		$m->addHTML(Html::elem('div',
							   ['class' => 'row bg-light mt-4 pt-3',
								'id'    => 'hgzweb-footer'],
							   $this->getFooterText()));
		
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
		$d->addHTML('<!DOCTYPE html>');
		
		// <head>
		// Kopfbereich
		$d->addHTML($this->head);
		
		// <body>
		// Inhaltsbereich
		$b->addHTML($this->body);

		// Fußbereich
		$b->addHTML($this->foot);
		
		// <body> in <html>
		$h->addHTML(Html::elem('body',
							   ['role' => 'document'],
							   $b->output()));
		
		// <html> in Dokument
		$d->addHTML(Html::elem('html',
							   ['lang' => 'de'],
							   $h->output()));

		
		// Dokument ausgeben
		echo $d->output();
	}
	
	/**
	 * getAbs()
	 * absoluten Pfad der Website ausgeben
	 */
	public static function getAbs() {
		return 'https://' . UserConfig::$var['host'] . '/' . UserConfig::$var['sub-path'];
	}

	/**
	 * sortArray()
	 * Array sortieren, vgl. array_multisort()
	 */		
	public static function sortArray() {
		$args = func_get_args();
		$data = array_shift($args);
		foreach ($args as $n => $field) {
			if (is_string($field)) {
				$tmp = [];
				foreach ($data as $key => $row)
					$tmp[$key] = $row[$field];
				$args[$n] = $tmp;
			}
		}
		$args[] = &$data;
		call_user_func_array('array_multisort', $args);
		return array_pop($args);
	}
}

?>
