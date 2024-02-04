<?php
/**
 * == HtmlService/Html ==
 * bootstrap html framework in ancona
 *
 * (C) 2015-2023 Hgzh
 *
 */

namespace Ancona\HtmlService;

class Html {

	// html content
	protected $content = '';
	
	// stack, for html tree
	private $stack;

	/**
	 * __construct()
	 * initializations
	 */
	public function __construct() {			
		$this->stack = new \SplStack;
	}

	/**
	 * elem()
	 * creates a raw html element
	 *
	 * @param tag html tag name
	 * @param args attributes array
	 * @param content tag content
	 * @param close close tag
	 */
	public static function elem( $tag, $args = [], $content = '', $close = true ) : string {
		// lowercase
		$tag = strtolower( $tag );

		// open tag and set attributes
		$txt = '<' . $tag;
		foreach ( $args as $k => $v ) {
			// skip empty attributes
			if ( $v === false || $v === '' ) {
				continue;
			}

			// attribute name
			$txt .= ' ' . strtolower( $k );

			// attribute value if not unary (HTML5)
			if ( $v !== true ) {
				$txt .= '="' . trim( $v ) . '"';
			}
		}
		$txt .= '>';

		// content
		$txt .= $content;

		// HTML5: no self-closing tags
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
		
		// return text
		return $txt;
	}

	/**
	 * openBlock()
	 * adds an element with the possibility to add more
	 * elements inside
	 *
	 * @param tag html tag name
	 * @param class additional classes
	 * @param style CSS style definitions
	 * @param id selector id
	 * @param role aria role
	 */
	public function openBlock( $tag, $class = false, $style = false, $id = false, $role = false ) {
		$elem = $this->elem(
			$tag,
			[
				'class' => $class,
				'style' => $style,
				'id'	   => $id,
				'role'  => $role
			],
			'',
			false
		);
		$this->content .= $elem;

		// add tag to the stack
		$this->stack->push( $tag );
	}

	/**
	 * closeBlock()
	 * closes an element opened with openBlock(). Uses a stack for closing
	 * elements in the right order.
	 *
	 * @param nr count of elements to close (default 1)
	 */
	public function closeBlock( $nr = 1 ) {
		for ( $i = 0; $i < $nr; $i++ ) {
			$tag = $this->stack->pop();
			$this->content .= '</' . $tag . '>';
		}
	}

	/**
	 * addInline()
	 * inserts a html element without further nesting possible
	 *
	 * @param tag html tag name
	 * @param content tag content
	 * @param class additional classes
	 * @param style CSS style definitions
	 * @param id selector id
	 */		
	public function addInline( $tag, $content = '', $class = false, $style = false, $id = false ) {
		$this->content .= $this->elem(
			$tag,
			[
				'class' => $class,
				'style' => $style,
				'id'    => $id
			],
			$content
		);
	}

	/**
	 * addHTML()
	 * adds html code at the current position
	 *
	 * @param code html to add
	 */
	public function addHTML( $code ) {
		$this->content .= $code;
	}

	/**
	 * openContainer()
	 * opens a bootstrap container
	 *
	 * @param size container width
	 * @param pclass additonal classes
	 * @param id selector id
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
	 * closes an already opened container
	 */
	public function closeContainer() {
		$this->closeBlock();
	}

	/**
	 * openRow()
	 * opens a new bootstrap row
	 *
	 * @param justify content justification
	 * @param pclass additional classes
	 * @param style CSS style definitions
	 * @param id selector id
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
	 * closes an already opened row
	 */
	public function closeRow() {
		$this->closeBlock();
	}

	/**
	 * openCol()
	 * opens a new bootstrap column
	 *
	 * @param device breakpoint value
	 * @param cols width (max. 12)
	 * @param pclass additional classes
	 * @param style CSS style definitions
	 * @param id selector id
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
	 * inserts a new heading
	 *
	 * @param level heading level
	 * @param text heading text
	 * @param class additional classes
	 * @param id selector id
	 */
	public function addHeading( $level, $text, $class = false, $id = false ) {
		$this->addInline( 'h' . $level, $text, $class, false, $id );
	}

	/**
	 * addParagraph()
	 * inserts a new paragraph
	 *
	 * @param text paragraph text
	 * @param class additional classes
	 * @param style CSS style definitions
	 */
	public function addParagraph( $text, $class = '', $style = '' ) {
		$this->addInline( 'p', $text, $class, $style );
	}
	
	/**
	 * addTableCell()
	 * inserts a new table cell
	 *
	 * @param content cell content
	 * @param tag cell type (th/td)
	 * @param class additional classes
	 * @param style CSS style definitions
	 */
	public function addTableCell( $content, $tag = 'td', $colspan = false, $rowspan = false, $class = false, $style = false ) {
		$elem = $this->elem(
			$tag,
			[
				'class' => $class,
				'style' => $style,
				'colspan' => $colspan,
				'rowspan' => $rowspan
			],
			$content
		);
		
		$this->content .= $elem;
	}	

	/**
	 * addLink()
	 * creates a hyperlink
	 *
	 * @param href link url
	 * @param text link text
	 * @param class additional classes
	 * @param target html link target
	 */
	public function addLink( $href, $text, $class = false, $target = false, $id = false, $title = false ) {
		$elem = $this->elem(
			'a',
			[
				'href'   => $href,
				'class'  => $class,
				'target' => $target,
				'id'     => $id,
				'title'  => $title
			],
			$text
		);

		$this->content .= $elem;
	}
	
	/**
	 * addModalToggleLink()
	 * inserts a link for opening a modal
	 *
	 * @param modal modal id
	 * @param icon link icon
	 * @param text link text
	 * @param tooltip link tooltip
	 * @param class additional classes
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
	 * inserts a link that opens an element via toggle
	 *
	 * @param type element type
	 * @param id element id
	 * @param text link text
	 * @param class additional classes
	 * @param tooltip link tooltip
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
	 * inserts a html list
	 *
	 * @param type sorted (ol) or unsorted (ul) list
	 * @param entries list entries
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
	 * inserts a bootstrap tab element
	 *
	 * @param name name of tab element
	 * @param entries tab entries
	 */
	public function addNav( $name, $entries ) {
		// open nav tabs
		$this->openBlock(
			'ul',
			'nav nav-tabs sticky-top bg-white pt-2',
			'z-index:999;top:3.8rem;',
			'tab-' . $name,
			'tabs'
		);

		// display single tabs
		$i = 0;
		foreach ( $entries as $e ) {
			if ( $i === 0 ) {
				// active tab on page load
				$class = 'nav-link active';
				$aria  = 'true';
			} else {
				// other tabs
				$class = 'nav-link';
				$aria  = 'false';
			}
			$this->addHTML( $this->elem(
				'li',
				[
					'class' => 'nav-item',
					'role'  => 'presentation'
				],
				$this->elem(
					'a',
					[
						'class'          => $class,
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

		// tab contents
		$this->openBlock( 'div', 'tab-content', '', 'tab-' . $name . '-container' );

		$i = 0;
		foreach ( $entries as $e ) {
			if ( $i === 0 ) {
				// active tab on page load
				$class = 'tab-pane fade show active';
			} else {
				// other tabs
				$class = 'tab-pane fade';
			}
			$this->addHTML( $this->elem(
				'div',
				[
					'class'           => $class,
					'id'              => 'tab-' . $name . '-' . $e['id'] . '-cont',
					'role'            => 'tabpanel',
					'aria-labelledby' => 'tab-' . $name . '-' . $e['id']
				],
				$e['content']
			) );
			$i++;
		}

		// close nav
		$this->closeBlock();
	}
	
	/**
	 * addAccordion()
	 * inserts a bootstrap accordion element
	 *
	 * @param name name of accordion element
	 * @param entries accordion entries
	 */
	public function addAccordion( $name, $entries ) {
		// accordion
		$this->openBlock( 'div', 'accordion', '', 'acc-' . $name );
		
		// display entries
		$i = 0;
		foreach ( $entries as $e ) {
			if ( isset( $e['html'] ) ) {
				$this->addHTML( $e['html'] );
				continue;
			}
			
			$this->openBlock( 'div', 'accordion-item' );
			
			// header
			$this->openBlock( 'div', 'accordion-header', '', 'acc-' . $name . '-' . $e['id'] . '-head' );
			$this->addHTML( $this->elem(
				'button',
				[
					'class'          => 'accordion-button collapsed p-2',
					'data-bs-toggle' => 'collapse',
					'data-bs-target' => '#acc-' . $name . '-' . $e['id'],
					'aria-expanded'  => 'false',
					'aria-controls'  => 'acc-' . $name . '-' . $e['id']
				],
				$e['title']
			) );
			$this->closeBlock();
			
			// content
			$this->addHTML( $this->elem(
				'div',
				[
					'id' => 'acc-' . $name . '-' . $e['id'],
					'class' => 'ccordion-collapse collapse',
					'aria-labelledby' => 'acc-' . $name . '-' . $e['id'] . '-head',
					'data-bs-parent'  => '#acc-' . $name
				],
				'',
				false
			) );
			$this->openBlock( 'div', 'accordion-body' );
			$this->addHTML( $e['content'] );
			$this->closeBlock();
			$this->addHTML( '</div>' );
			
			$this->closeBlock();
		}
		
		// close accordion
		$this->closeBlock();
	}
	
	/**
	 * addModal()
	 * inserts a bootstrap modal
	 *
	 * @param name dialog name
	 * @param title dialog title
	 * @param content dialog content
	 * @param footer footer content
	 */
	public function addModal( $name, $title, $content, $footer = false, $class = '' ) {
		// open modal
		$this->addHTML( $this->elem(
			'div',
			[
				'class'    => 'modal fade',
				'id'       => 'mod-' . $name,
				'tabindex' => '-1',
				'aria-labelledby' => 'mod-' . $name  . '-label',
				'aria-hidden'     => 'true'
			],
			'',
			false
		) );
		$this->openBlock( 'div', 'modal-dialog modal-dialog-scrollable ' . $class );
		$this->openBlock( 'div', 'modal-content' );
		
		// header
		$this->openBlock( 'div', 'modal-header' );
		$this->addHeading( 5, $title, 'modal-title', 'mod-' . $name . '-label' );
		$this->addHTML( $this->elem(
			'button',
			[
				'type'            => 'button',
				'class'           => 'btn-close',
				'data-bs-dismiss' => 'modal',
				'aria-label'      => 'Schließen'
			]
		) );
		$this->closeBlock();
		
		// content
		$this->openBlock( 'div', 'modal-body' );
		$this->addHTML( $content );
		$this->closeBlock();
		
		// footer
		if ( $footer !== false ) {
			$this->openBlock( 'div', 'modal-footer' );
			$this->addHTML( $footer );
			$this->closeBlock();
		}
		
		// close modal
		$this->closeBlock( 2 );
		$this->addHTML( '</div>' );
	}

	/**
	 * addOffcanvas()
	 * insert a bootstrap offcanvas element
	 *
	 * @param name offcanvas name
	 * @param title	offcanvas title
	 * @param content offcanvas content
	 * @param position display position
	 */	
	public function addOffcanvas( $name, $title, $content, $position = 'end' ) {
		// open offcanvas
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
		
		// header
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
		
		// content
		$this->openBlock( 'div', 'offcanvas-body' );
		$this->addHTML( $content );
		$this->closeBlock();
		
		// close offcanvas
		$this->addHTML( '</div>' );
	}
	
	/**
	 * addPagination()
	 * insert a bootstrap pagination element
	 *
	 * @param name pagination name
	 * @param target target element of pagination
	 * @param elements elements by page in target element
	 * @param size pagination size
	 * @param pos position
	 */
	public function addPagination( $name, $target, $elements = 10, $size = '', $pos = '' ) {
		// open pagination nav
		$this->addHTML( $this->elem(
			'nav',
			[
				'class'               => 'anc-pagination',
				'data-anc-name'       => $name,
				'data-anc-target'     => $target,
				'data-anc-elements'   => $elements
			],
			'',
			false ) );
		
		// pagination list
		$class = 'pagination';
		if ( $size !== '' ) $class .= ' pagination-' . $size;
		if ( $pos  !== '' ) $class .= ' justify-content-' . $pos;
		$this->openBlock( 'ul', $class );
		$this->addHTML( $this->elem(
			'li',
			[
				'class'         => 'page-item',
				'data-anc-page' => 1
			],
			'<a class="page-link" href="#">1</a>'
		) );
		$this->closeBlock();
		
		// close nav
		$this->addHTML( '</nav>' );
	}
	
	/**
	 * output()
	 * returns the created html
	 */
	public function output() : string {
		return $this->content;
	}
}

?>