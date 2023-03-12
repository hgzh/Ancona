<?php
/**
 * ##### html.php #####
 * Ancona: Bootstrap-Html-Framework
 *
 * (C) 2015-2023 Hgzh
 *
 */

namespace Ancona\HtmlService;

/**
 * ##### CLASS Html CLASS #####
 * Html-Strukturen für Bootstrap erstellen
 */
class Html {

	// Inhalt
	protected $content = '';
	
	// Stack, für HTML-Baum
	private $stack;

	/**
	 * Klassenkonstruktor
	 * Initialisierungen
	 */
	public function __construct() {			
		$this->stack = new \SplStack;
	}

	/**
	 * elem()
	 * erzeugt ein rohes HTML-Element
	 *
	 * Parameter
	 * - tag	 : HTML-Tag
	 * - args	 : Array mit Attributen
	 * - content : Inhalt des Tags
	 * - close	 : Tag wieder schließen
	 */
	public static function elem( $tag, $args = [], $content = '', $close = true ) {
		// Lowercase
		$tag = strtolower( $tag );

		// Tag öffnen und Attribute eintragen
		$txt = '<' . $tag;
		foreach ( $args as $k => $v ) {
			// leere Attribute überspringen
			if ( $v === false || $v === '' ) {
				continue;
			}

			// Attributname
			$txt .= ' ' . strtolower( $k );

			// Attributwert, wenn nicht alleinstehend (HTML5)
			if ( $v !== true ) {
				$txt .= '="' . trim( $v ) . '"';
			}
		}
		$txt .= '>';

		// Inhalt
		$txt .= $content;

		// HTML5: keine selbstschließenden Tags
		if ( $close === true ) {
			switch ( $tag ) {
				case 'br' :
				case 'hr' :
					break;
				default:
					$txt .= '</' . $tag . '>';
					break;
			}
		}
		
		// Text zurückgeben
		return $txt;
	}

	/**
	 * openBlock()
	 * fügt ein HTML-Element mit der Möglichkeit ein, weitere darinliegende Elemente zu definieren.
	 *
	 * Parameter
	 * - tag   : Name des Tags
	 * - class : Klassenangaben
	 * - style : CSS-Style-Angaben
	 * - id    : Selektor
	 * - role  : Role-Angabe
	 */
	public function openBlock( $tag, $class = false, $style = false, $id = false, $role = false ) {
		$elem = $this->elem( $tag,
							 [ 'class' => $class,
							   'style' => $style,
							   'id'	   => $id,
							   'role'  => $role
							 ],
							 '',
							 false );
		$this->content .= $elem;

		// auf den Stack
		$this->stack->push( $tag );
	}

	/**
	 * closeBlock()
	 * beendet ein mit openBlock() geöffnetes Element. Diese Funktion benutzt einen Stack, das zuletzt
	 * geöffnete Tag wird als erstes wieder geschlossen.
	 *
	 * Parameter
	 * - nr : Anzahl der mit einem Aufruf zu schließenden Tags
	 */
	public function closeBlock( $nr = 1 ) {
		for ( $i = 0; $i < $nr; $i++ ) {
			$tag = $this->stack->pop();
			$this->content .= '</' . $tag . '>';
		}
	}

	/**
	 * addInline()
	 * fügt ein HTML-Element ohne Möglichkeit weiter Definition ein.
	 *
	 * Parameter
	 * - tag     : Name des Tags
	 * - content : Inhalt des Tags
	 * - class   : Klassenangaben
	 * - style   : CSS-Style-Angaben
	 * - id      : ID
	 */		
	public function addInline( $tag, $content = '', $class = false, $style = false, $id = false ) {
		$this->content .= $this->elem( $tag,
									   [ 'class' => $class,
									     'style' => $style,
										 'id'    => $id
									   ],
									   $content );
	}

	/**
	 * addHTML()
	 * fügt beliebigen HTML-Code an der aktuellen Position ein
	 *
	 * Parameter
	 * - code : einzufügender Code
	 */
	public function addHTML($code) {
		$this->content .= $code;
	}

	/**
	 * openContainer()
	 * öffnet einen Bootstrap-Container
	 *
	 * Parameter
	 * - size   : Breite des Containers
	 * - pclass : CSS-Klassen
	 * - id     : CSS-ID
	 */
	public function openContainer( $size = false, $pclass = false, $id = false ) {
		$class = 'container';
		if ( $size ) {
			$class .= '-' . $size;
		}
		if ( $pclass ) {
			$class .= ' ' . $pclass;
		}

		$this->openBlock( 'div', $class, false, $id );
	}

	/**
	 * closeContainer()
	 * schließt einen zuvor geöffneten Container
	 */
	public function closeContainer() {
		$this->closeBlock();
	}

	/**
	 * openRow()
	 * beginnt eine neue Bootstrap-Zeile
	 *
	 * Parameter
	 * - justify : Ausrichtung des Inhalts
	 * - pclass  : CSS-Klassen
	 */
	public function openRow( $justify = '', $pclass = false, $style = false, $id = false ) {
		$class = 'row';
		if ( $justify != false ) {
			$class .= ' justify-content-' . $justify;
		}
		if ( $class != false ) {
			$class .= ' ' . $pclass;
		}

		$this->openBlock( 'div', $class, $style, $id );
	}

	/**
	 * closeRow()
	 * schließt eine zuvor geöffnete Zeile
	 */
	public function closeRow() {
		$this->closeBlock();
	}

	/**
	 * openCol()
	 * öffnet eine Bootstrap-Spalte
	 *
	 * Parameter
	 * - device : Breakpoint-Angabe
	 * - cols	: Breitenangabe (max. 12)
	 * - pclass	: CSS-Klassen
	 */
	public function openCol( $device = false, $cols = 0, $pclass = false, $style = false, $id = false ) {
		$class = 'col';
		if ( $device != false ) {
			$class .= '-' . $device;
		}
		if ( $cols != 0 ) {
			$class .= '-' . $cols;
		}
		if ( $class != false ) {
			$class .= ' ' . $pclass;
		}

		$this->openBlock( 'div', $class, $style, $id );
	}

	/**
	 * closeCol()
	 * schließt eine zuvor geöffnete Spalte
	 */
	public function closeCol() {
		$this->closeBlock();
	}

	/**
	 * addHeading()
	 * fügt eine Überschrift ein
	 *
	 * Parameter
	 * - level : Überschriftenebene
	 * - text  : Text der Überschrift
	 * - class : CSS-Klasse
	 * - id    : ID
	 */
	public function addHeading( $level, $text, $class = false, $id = false ) {
		$this->addInline( 'h' . $level, $text, $class, false, $id );
	}

	/**
	 * addParagraph()
	 * fügt einen Absatz ein
	 *
	 * Parameter
	 * - text  : Absatztext
	 * - class : CSS-Klassen
	 * - style : CSS-Styles
	 */
	public function addParagraph( $text, $class = '', $style = '' ) {
		$this->addInline( 'p', $text, $class, $style );
	}

	/**
	 * addLink()
	 * fügt einen Hyperlink (a-Element) ein
	 *
	 * Parameter
	 * - href   : URL
	 * - text   : Linktext
	 * - class  : CSS-Klassen
	 * - target : Target
	 */
	public function addLink( $href, $text, $class = false, $target = false, $id = false, $title = false ) {
		$elem = $this->elem( 'a',
							 [ 'href'   => $href,
							   'class'  => $class,
							   'target' => $target,
							   'id'     => $id,
							   'title'  => $title
							 ],
							 $text );

		$this->content .= $elem;
	}
	
	/**
	 * addModalToggleLink()
	 * fügt einen Link ein, der ein Modal-Element öffnet
	 *
	 * Parameter
	 * - modal   : ID des Modals
	 * - icon    : Icon des Links
	 * - text    : Linktext
	 * - tooltip : Link-Tooltip
	 * - class   : CSS-Klassen
	 */
	public function addModalToggleLink($modal, $icon, $text = '', $tooltip = '', $class = '') {
		$this->addToggleLink(
			'modal',
			$modal,
			$this->elem(
				'i',
				[ 'class' => 'bi bi-' . $icon . ' ' . $class ]
			) . ( $text != ''
					? ' ' . $text
					: ''
				),
			false,
			$tooltip
		);
	}

	/**
	 * addToggleLink()
	 * fügt einen Link ein, der ein Element per Toggle öffnet
	 *
	 * Parameter
	 * - type    : Typ des Elements
	 * - id      : ID des Elements
	 * - icon    : Icon des Links
	 * - text    : Linktext
	 * - tooltip : Link-Tooltip
	 * - class   : CSS-Klassen
	 */
	public function addToggleLink($type, $id, $text = '', $class = false, $tooltip = false) {
		$elem = $this->elem(
			'a',
			[
				'href'           => '#',
				'role'           => 'button',
				'data-bs-toggle' => $type,
				'data-bs-target' => '#' . $id,
				'class'          => $class
			],
			$this->elem(
				'span',
				[ 'title' => $tooltip ],
				$text
			)
		);
		
		$this->content .= $elem;
	}	
	
	/**
	 * addList()
	 * fügt eine HTML-Liste ein
	 *
	 * Parameter
	 * - type 	 : sortierte (ol) oder unsortierte (ul) Liste
	 * - entries : Array mit den Einträgen
	 */
	public function addList( $type, $entries ) {
		$this->openBlock( $type );
		foreach ( $entries as $e ) {
			$this->addInline( 'li', $e );
		}
		$this->closeBlock();
	}

	/**
	 * addNav()
	 * fügt ein Bootstrap-Tab-Element ein
	 *
	 * Parameter
	 * - name 	 : Name des Tab-Elements
	 * - entries : Array mit den einzelnen Tabs
	 */
	public function addNav( $name, $entries ) {
		// Nav-Tabs öffnen
		$this->openBlock( 'ul', 'nav nav-tabs sticky-top bg-white pt-2', 'z-index:999;top:3.8rem;', 'tab-' . $name, 'tabs' );

		// einzelne Tabs darstellen
		$i = 0;
		foreach ( $entries as $e ) {
			if ( $i === 0 ) {
				// aktiver Tab beim ersten Laden der Seite
				$class = 'nav-link active';
				$aria  = 'true';
			} else {
				// alle anderen Tabs
				$class = 'nav-link';
				$aria  = 'false';
			}
			$this->addHTML( $this->elem( 'li',
										 [ 'class' => 'nav-item',
										   'role'  => 'presentation'
										 ],
										 $this->elem( 'a',
													  [ 'class'          => $class,
													    'id'             => 'tab-' . $name . '-' . $e['id'],
													    'data-bs-toggle' => 'tab',
													    'href'           => '#tab-' . $name . '-' . $e['id'] . '-cont',
													    'role'           => 'tab',
													    'aria-controls'  => 'tab-' . $name . '-' . $e['id'] . '-cont',
													    'aria-selected'  => $aria
													  ],
													  $e['text']
												   )
									   ) );
			$i++;
		}
		$this->closeBlock();

		// Tab-Inhalte
		$this->openBlock( 'div', 'tab-content', '', 'tab-' . $name . '-container' );

		$i = 0;
		foreach ( $entries as $e ) {
			if ( $i === 0 ) {
				// aktiver Tab beim ersten Laden der Seite
				$class = 'tab-pane fade show active';
			} else {
				// alle anderen Tabs
				$class = 'tab-pane fade';
			}
			$this->addHTML( $this->elem( 'div',
										 [ 'class' => $class,
										   'id'    => 'tab-' . $name . '-' . $e['id'] . '-cont',
										   'role'  => 'tabpanel',
										   'aria-labelledby' => 'tab-' . $name . '-' . $e['id']
										 ],
										 $e['content']
										) );
			$i++;
		}

		// Nav schließen
		$this->closeBlock();
	}
	
	/**
	 * addAccordion()
	 * fügt ein Bootstrap-Tab-Element ein
	 *
	 * Parameter
	 * - name 	 : Name des Accordion-Elements
	 * - entries : Array mit den einzelnen Elementen
	 */
	public function addAccordion( $name, $entries ) {
		// Nav-Tabs öffnen
		$this->openBlock( 'div', 'accordion', '', 'acc-' . $name );
		
		// einzelne Elemente darstellen
		$i = 0;
		foreach ( $entries as $e ) {
			if ( isset( $e['html'] ) ) {
				$this->addHTML( $e['html'] );
				continue;
			}
			
			$this->openBlock( 'div', 'accordion-item' );
			
			// Header
			$this->openBlock( 'div', 'accordion-header', '', 'acc-' . $name . '-' . $e['id'] . '-head' );
			$this->addHTML( $this->elem( 'button',
										 [ 'class'          => 'accordion-button collapsed p-2',
										   'data-bs-toggle' => 'collapse',
										   'data-bs-target' => '#acc-' . $name . '-' . $e['id'],
										   'aria-expanded'  => 'false',
										   'aria-controls'  => 'acc-' . $name . '-' . $e['id']
										 ],
										 $e['title']
										) );
			$this->closeBlock();
			
			// Inhalt
			$this->addHTML( $this->elem( 'div',
										 [ 'id' => 'acc-' . $name . '-' . $e['id'],
										   'class' => 'ccordion-collapse collapse',
										   'aria-labelledby' => 'acc-' . $name . '-' . $e['id'] . '-head',
										   'data-bs-parent'  => '#acc-' . $name
										 ],
										 '',
										 false ) );
			$this->openBlock( 'div', 'accordion-body' );
			$this->addHTML( $e['content'] );
			$this->closeBlock();
			$this->addHTML( '</div>' );
			
			$this->closeBlock();
		}
		
		// Accordion schließen
		$this->closeBlock();
	}
	
	/**
	 * addModal()
	 * fügt ein Bootstrap-Dialog-Element ein
	 *
	 * Parameter
	 * - name 	 : Name des Dialogs
	 * - title	 : Titel des Dialogs
	 * - content : Inhalt des Dialogs
	 * - footer  : Inhalt des Fußbereichs
	 */
	public function addModal( $name, $title, $content, $footer = false, $class = '' ) {
		// Modal beginnen
		$this->addHTML( $this->elem( 'div',
									 [ 'class'    => 'modal fade',
									   'id'       => 'mod-' . $name,
									   'tabindex' => '-1',
									   'aria-labelledby' => 'mod-' . $name  . '-label',
									   'aria-hidden'     => 'true'
									 ],
									 '',
									 false ) );
		$this->openBlock( 'div', 'modal-dialog modal-dialog-scrollable ' . $class );
		$this->openBlock( 'div', 'modal-content' );
		
		// Kopfbereich
		$this->openBlock( 'div', 'modal-header' );
		$this->addHeading( 5, $title, 'modal-title', 'mod-' . $name . '-label' );
		$this->addHTML( $this->elem( 'button',
									 [ 'type'            => 'button',
									   'class'           => 'btn-close',
									   'data-bs-dismiss' => 'modal',
									   'aria-label'      => 'Schließen'
									 ]
									) );
		$this->closeBlock();
		
		// Inhaltsbereich
		$this->openBlock( 'div', 'modal-body' );
		$this->addHTML( $content );
		$this->closeBlock();
		
		// Fußbereich
		if ( $footer !== false ) {
			$this->openBlock( 'div', 'modal-footer' );
			$this->addHTML( $footer );
			$this->closeBlock();
		}
		
		// Modal schließen
		$this->closeBlock( 2 );
		$this->addHTML( '</div>' );
	}

	/**
	 * addOffcanvas()
	 * fügt ein Bootstrap-Offcanvas-Element ein
	 *
	 * Parameter
	 * - name 	  : Name des Offcanvas
	 * - title	  : Titel des Offcanvas
	 * - content  : Inhalt des Offcanvas
	 * - position : Anzeigeposition
	 */	
	public function addOffcanvas( $name, $title, $content, $position = 'end' ) {
		// Modal beginnen
		$this->addHTML( $this->elem(
			'div',
			[
				'class'           => 'offcanvas offcanvas-' . $position,
				'id'              => 'ofc-' . $name,
				'tabindex'        => '-1',
				'aria-labelledby' => 'ofc-' . $name  . '-label',
			],
			'',
			false ) );
		
		// Kopfbereich
		$this->openBlock( 'div', 'offcanvas-header' );
		$this->addHeading( 5, $title, 'offcanvas-title', 'ofc-' . $name . '-label' );
		$this->addHTML( $this->elem(
			'button',
			[
				'type'            => 'button',
				'class'           => 'btn-close',
				'data-bs-dismiss' => 'offcanvas',
				'aria-label'      => 'Schließen'
			] ) );
		$this->closeBlock();
		
		// Inhaltsbereich
		$this->openBlock( 'div', 'offcanvas-body' );
		$this->addHTML( $content );
		$this->closeBlock();
		
		// Offcanvas schließen
		$this->addHTML( '</div>' );
	}
	
	/**
	 * output()
	 * gibt das erzeugte HTML zurück
	 */
	public function output() {
		return $this->content;
	}
}

?>