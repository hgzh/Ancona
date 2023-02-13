<?php
/**
 * ##### form.php #####
 * hgzWeb: Bootstrap-Form-Framework
 *
 * (C) 2021-2023 Hgzh
 *
 */

namespace hgzWeb\FormService;

use hgzWeb\HtmlService\Html as Html;
use hgzWeb\ExceptionService as Exception;

/**
 * ##### CLASS bsForm CLASS #####
 * Bootstrap-Form
 */
class bsForm extends Html {
	
	// Formname
	public $name = '';
	
	/**
	 * Klassenkonstruktor
	 * Initialisierungen
	 */
	public function __construct( $name, $action = false, $method = false, $class = false ) {			
		parent::__construct();
		
		$this->name     = $name;
		$this->content .= $this->elem( 'form',
									   [ 'id' => 'hgzform-' . $name,
										 'action' => $action,
										 'method' => $method,
										 'class'  => $class
									   ],
									   '',
									   false );
	}
	
	public function input( $a ) {
		// Parameter prüfen
		$a['float']       ??= false;
		$a['placeholder'] ??= ( isset( $a['label'] ) ? $a['label'] : '...' );
		$a['maxlength']   ??= false;
		$a['required']    ??= false;
		$a['readonly']    ??= false;
		$a['disabled']    ??= false;
		$a['value']       ??= false;
		if ( !isset( $a['name'] ) ) {
			throw new Exception\Argument( __CLASS__, 'input()', 'name= fehlt für input' );
		}
		if ( !isset( $a['type'] ) ) {
			throw new Exception\Argument( __CLASS__, 'input()', 'type= fehlt für input' );
		}
		
		// wenn label oder text, dann umschließendes Element
		if ( isset( $a['label'] ) || isset( $a['text'] ) ) {
			$enclose = true;
		} else {
			$enclose = false;
		}
		
		// Tag-Text
		$text = '';
		
		// umschließendes div beginnen
		if ( $enclose == true ) {
			$text .= $this->elem( 'div',
								  [ 'class' => ( $a['float'] == true ? 'form-floating' : '' )
								               . ( isset( $a['class'] )
												   ? ' ' . $a['class']
												   : ''
												 )
								  ],
								  '',
								  false );
		}
		
		// Label (normal)
		if ( isset( $a['label'] ) && $a['float'] == false ) {
			$text .= $this->elem( 'label',
								  [ 'for'   => 'lsm-' . $this->name . '-' . $a['name'],
								   	'class' => 'form-label'
								  ],
								  $a['label'] . ':',
								  true );
		}
		
		// Input
		$text .= $this->elem( 'input', [ 'type'             => $a['type'],
									     'id'               => 'lsm-' . $this->name . '-' . $a['name'],
									     'name'             => $a['name'],
									     'class'            => 'form-control'
									                           . ( isset( $a['size'] ) 
																   ? ' form-control-' . $a['size']
																   : ''
																 ),
									     'placeholder'      => $a['placeholder'],
									     'maxlength'        => $a['maxlength'],
									     'required'         => $a['required'],
									     'readonly'         => $a['readonly'],
									     'disabled'         => $a['disabled'],
									     'value'            => $a['value'],
									     'aria-describedby' => ( isset( $a['text'] ) 
																 ? 'lsm-' . $this->name . '-' . $a['name'] . '-text' 
																 : false
															   )
									   ],
							 '',
							 false );
		
		// Label für form-floating
		if ( isset( $a['label'] ) && $a['float'] == true ) {
			$text .= $this->elem( 'label', [ 'for'   => 'lsm-' . $this->name . '-' . $a['name'],
										     'class' => 'form-label'
										   ],
								  $a['label'],
								  true );
		}
		
		// Hilfetext
		if ( isset( $a['text'] ) ) {
			$text .= $this->elem( 'div', [ 'id'    => 'lsm-' . $this->name . '-' . $a['name'] . '-text',
										   'class' => 'form-text'
										 ],
								  $a['text'],
								  true );
		}
		
		// umschließendes div beenden
		if ( $enclose == true ) {
			$text .= '</div>';
		}
		
		$this->content .= $text;
	}
	
	public function textarea($a) {
		// Parameter prüfen
		$a['height']      ??= '10em';
		$a['float']       ??= false;
		$a['placeholder'] ??= ( isset( $a['label'] ) ? $a['label'] : '...' );
		$a['maxlength']   ??= false;
		$a['required']    ??= false;
		$a['readonly']    ??= false;
		$a['disabled']    ??= false;
		$a['value']       ??= false;
		if ( !isset( $a['name'] ) ) {
			throw new Exception\Argument( __CLASS__, 'textarea()', 'name= fehlt für textarea' );
		}
		
		// wenn label oder text, dann umschließendes Element
		if ( isset( $a['label'] ) || isset( $a['text'] ) ) {
			$enclose = true;
		} else {
			$enclose = false;	
		}

		// Tag-Text
		$text = '';
		
		// umschließendes div beginnen
		if ( $enclose == true ) {
			$text .= $this->elem( 'div', [ 'class' => ( $a['float'] == true 
													    ? 'form-floating'
													    : ''
													  )
										              . ( isset( $a['class'] ) 
														  ? ' ' . $a['class']
														  : ''
														)
										 ],
								  '',
								  false );
		}
		
		// Label (normal)
		if ( isset( $a['label'] ) && $a['float'] == false) {
			$text .= $this->elem( 'label', [ 'for'   => 'lsm-' . $this->name . '-' . $a['name'],
										     'class' => 'form-label'
										   ],
								  $a['label'] . ':',
								  true );
		}
		
		// Textarea
		$text .= $this->elem( 'textarea', [ 'id'               => 'lsm-' . $this->name . '-' . $a['name'],
										    'name'             => $a['name'],
										    'class'            => 'form-control',
										    'style'            => 'height:' . $a['height'],
										    'placeholder'      => $a['placeholder'],
										    'maxlength'        => $a['maxlength'],
										    'required'         => $a['required'],
										    'readonly'         => $a['readonly'],
										    'disabled'         => $a['disabled'],
										    'aria-describedby' => ( isset( $a['text'] )
																    ? 'lsm-' . $this->name . '-' . $a['name'] . '-text'
																    : false
																  )
										 ],
							  $a['value'],
							  true );

		// Label für form-floating
		if ( isset( $a['label'] ) && $a['float'] == true ) {
			$text .= $this->elem( 'label', [ 'for'   => 'lsm-' . $this->name . '-' . $a['name'],
										     'class' => 'form-label'
										   ],
								  $a['label'],
								  true );
		}
		
		// Hilfetext
		if ( isset( $a['text'] ) ) {
			$text .= $this->elem( 'div', [ 'id'    => 'lsm-' . $this->name . '-' . $a['name'] . '-text',
										   'class' => 'form-text'
										 ],
								  $a['text'],
								  true );
		}
		
		// umschließendes div beenden
		if ( $enclose == true ) {
			$text .= '</div>';
		}
		
		$this->content .= $text;
	}
	
	public function datetime($a) {
		// Parameter prüfen
		$a['float']       ??= false;
		$a['placeholder'] ??= ( isset( $a['label'] ) ? $a['label'] : '...' );
		$a['required']    ??= false;
		$a['readonly']    ??= false;
		$a['disabled']    ??= false;
		$a['value']       ??= false;
		if ( !isset( $a['name'] ) ) {
			throw new Exception\Argument( __CLASS__, 'datetime()', 'name= fehlt für datetime' );
		}
		if ( !isset( $a['type'] ) ) {
			throw new Exception\Argument( __CLASS__, 'datetime()', 'type= fehlt für datetime' );
		}
		
		// wenn label oder text, dann umschließendes Element
		if ( isset($a['label'] ) || isset( $a['text'] ) ) {
			$enclose = true;
		} else {
			$enclose = false;
		}
		
		// Tag-Text
		$text = '';
		
		// umschließendes div beginnen
		if ( $enclose == true ) {
			$text .= $this->elem( 'div', [ 'class' => ( $a['float'] == true
													    ? 'form-floating'
													    : ''
													  )
										              . ( isset( $a['class'] )
														  ? ' ' . $a['class']
														  : ''
														)
										 ],
								  '',
								  false );
		}

		// Label (normal)
		if ( isset( $a['label'] ) && $a['float'] == false ) {
			$text .= $this->elem( 'label', [ 'for'   => 'lsm-' . $this->name . '-' . $a['name'],
										     'class' => 'form-label'
										   ],
								  $a['label'] . ':',
								  true );
		}
		
		// Input
		$text .= $this->elem( 'input', [ 'type'             => 'text',
									     'id'               => 'lsm-' . $this->name . '-' . $a['name'],
									     'name'             => $a['name'],
									     'class'            => 'form-control datetimepicker-input lsm-date-' . $a['type'],
									     'data-target'      => '#' . 'lsm-' . $this->name . '-' . $a['name'],
									     'placeholder'      => $a['placeholder'],
									     'required'         => $a['required'],
									     'readonly'         => $a['readonly'],
									     'disabled'         => $a['disabled'],
									     'value'            => $a['value'],
									     'aria-describedby' => ( isset( $a['text'] )
																 ? 'lsm-' . $this->name . '-' . $a['name'] . '-text'
																 : false
															   )
									   ],
							  '',
							  false);

		// Label für form-floating
		if ( isset( $a['label'] ) && $a['float'] == true ) {
			$text .= $this->elem( 'label', [ 'for'   => 'lsm-' . $this->name . '-' . $a['name'],
										     'class' => 'form-label'
										   ],
								 $a['label'],
								 true );
		}
		
		// Hilfetext
		if ( isset( $a['text'] ) ) {
			$text .= $this->elem( 'div', [ 'id'    => 'lsm-' . $this->name . '-' . $a['name'] . '-text',
										   'class' => 'form-text'
										 ],
								 $a['text'],
								 true );
		}
		
		// umschließendes div beenden
		if ( $enclose == true ) {
			$text .= '</div>';
		}
		
		$this->content .= $text;
	}
	
	public function select($a) {
		// Parameter prüfen
		$a['float']    ??= false;
		$a['disabled'] ??= false;
		if ( !isset( $a['name'] ) ) {
			throw new Exception\Argument( __CLASS__, 'select()', 'name= fehlt für select' );
		}
		
		// wenn label oder text, dann umschließendes Element
		if ( isset( $a['label'] ) || isset( $a['text'] ) ) {
			$enclose = true;
		} else {
			$enclose = false;
		}

		// Tag-Text
		$text = '';		
		
		// umschließendes div beginnen
		if ( $enclose == true ) {
			$text .= $this->elem( 'div', [ 'class' => ( $a['float'] == true
													    ? 'form-floating'
													    : ''
													  )
										              . ( isset($a['class'] )
														  ? ' ' . $a['class']
														  : ''
														)
										 ],
								  '',
								  false );
		}
		
		// Label (normal)
		if ( isset( $a['label'] ) && $a['float'] == false ) {
			$text .= $this->elem( 'label', [ 'for'   => 'lsm-' . $this->name . '-' . $a['name'],
										     'class' => 'form-label'
										   ],
								  $a['label'] . ':',
								  true );
		}
		
		// Select
		$text .= $this->elem( 'select', [ 'id'               => 'lsm-' . $this->name . '-' . $a['name'],
										  'name'             => $a['name'],
										  'class'            => 'form-control'
										                        . ( isset( $a['size'] )
																    ? ' form-control-' . $a['size']
																    : ''
																  ),
										  'disabled'         => $a['disabled'],
										  'aria-describedby' => ( isset($a['text'] )
																  ? 'lsm-' . $this->name . '-' . $a['name'] . '-text'
																  : false
																)
									   ],
							  '',
							  false );
		
		// Options
		if ( isset( $a['*'] ) ) {
			foreach ( $a['*'] as $v ) {
				// Parameter prüfen
				if ( !isset( $v['selected'] ) ) {
					$v['selected'] = false;
				}
				
				// Option anlegen
				$text .= $this->elem( 'option', [ 'value'    => $v['value'],
												  'selected' => $v['selected']
												],
									  $v['text'],
									  true );
			}
		}
		
		// Select schließen
		$text .= '</select>';
		
		// Label für form-floating
		if ( isset( $a['label'] ) && $a['float'] == true ) {
			$text .= $this->elem( 'label', [ 'for'   => 'lsm-' . $this->name . '-' . $a['name'],
										     'class' => 'form-label'
										   ],
								 $a['label'],
								 true );
		}
		
		// Hilfetext
		if ( isset( $a['text'] ) ) {
			$text .= $this->elem( 'div', [ 'id'    => 'lsm-' . $this->name . '-' . $a['name'] . '-text',
										   'class' => 'form-text'
										 ],
								 $a['text'],
								 true );
		}
		
		// umschließendes div beenden
		if ( $enclose == true ) {
			$text .= '</div>';
		}
		
		$this->content .= $text;
	}
	
	public function checkbox($a) {
		// Parameter prüfen
		$a['float']    ??= false;
		$a['inline']   ??= false;
		$a['disabled'] ??= false;
		$a['checked']  ??= false;
		$a['value']    ??= false;
		if ( !isset( $a['name'] ) ) {
			throw new Exception\Argument( __CLASS__, 'checkbox()', 'name= fehlt für checkbox' );
		}
		if ( !isset( $a['type'] ) ) {
			throw new Exception\Argument( __CLASS__, 'checkbox()', 'type= fehlt für checkbox' );
		}

		// wenn label oder text, dann umschließendes Element
		if ( isset( $a['label'] ) ) {
			$enclose = true;
		} else {
			$enclose = false;
		}

		// Tag-Text
		$text = '';		
		
		// umschließendes div beginnen
		if ($enclose == true) {
			$text .= $this->elem( 'div', [ 'class' => 'form-check'
										              . ( $a['inline'] == true
														  ? ' form-check-inline'
														  : ''
														)
										              . ( isset( $a['class'] )
														  ? ' ' . $a['class']
														  : ''
														)
										],
								  '',
								  false );
		}

		// Checkbox
		$text .= $this->elem( 'input', [ 'type'             => $a['type'],
									     'id'               => 'lsm-' . $this->name . '-' . $a['name'],
									     'name'             => $a['name'],
									     'class'            => 'form-check-input'
										                       . ( isset($a['inpclass'] )
																   ? ' ' . $a['inpclass']
																   : ''
																 ),
									     'disabled'         => $a['disabled'],
									     'checked'          => $a['checked'],
									     'value'            => $a['value'],
									     'aria-describedby' => ( isset($a['text'] )
																 ? 'lsm-' . $this->name . '-' . $a['name'] . '-text'
																 : false
															   )
									   ],
							  '',
							  false );
		
		// Label
		if ( isset($a['label'])) {
			$text .= $this->elem( 'label', [ 'for'   => 'lsm-' . $this->name . '-' . $a['name'],
										     'class' => 'form-check-label'
										   ],
								  $a['label'],
								  true );
		}
		
		// umschließendes div beenden
		if ( $enclose == true ) {
			$text .= '</div>';
		}

		$this->content .= $text;
	}
	
	public function button($a) {
		// Tag-Text
		$text = '';		
		
		// Button-Group öffnen, falls nötig
		if ( isset( $a['group'] ) ) {
			// size
			if ( isset( $a['group']['size'] ) ) {
				$a['group']['size'] = ' btn-group-' . $a['group']['size'];
			} else {
				$a['group']['size'] = '';
			}

			// vertical
			if ( isset( $a['group']['vertical'] ) ) {
				$a['group']['vertical'] = 'btn-group-vertical';
			} else {
				$a['group']['vertical'] = 'btn-group';				
			}

			// grid
			if ( isset($a['group']['grid'] ) ) {
				$a['group']['grid'] = ' ' . $a['group']['grid'];
			} else {
				$a['group']['grid'] = '';
			}

			$text .= $this->elem( 'div', [ 'class' => $a['group']['vertical']
										              . $a['group']['size']
										              . $a['group']['grid']
										              . ' ' . $a['group']['class']
										 ],
								  '',
								  false );
		}

		foreach ( $a['*'] as $b ) {			
			// Parameter prüfen
			$b['disabled'] ??= false;
			$b['value']    ??= false;
			$b['name']     ??= false;
			if ( !isset( $b['type'] ) ) {
				throw new Exception\Argument( __CLASS__, 'button()', 'type= fehlt für button' );
			}
						
			// Input einfügen
			$text .= $this->elem( 'button', [ 'type'     => $b['type'],
											  'id'       => 'lsm-' . $this->name . '-'
											                . ( $b['name'] == false 
															    ? $b['type']
															    : $b['name']
															  ),
											  'name'     => $b['name'],
											  'class'    => 'btn'
											                . ( isset( $b['color'] )
																? ' btn-' . $b['color']
																: ''
															  )
											                . ( isset( $b['class'] )
															    ? ' ' . $b['class']
															    : ''
															  ),
											  'disabled' => $b['disabled'],
											  'value'    => $b['value']
											],
								  $b['text'],
								  true );
		}

		// Button-Group wieder schließen
		if ( isset( $a['group'] ) ) {
			$text .= '</div>';
		}

		$this->content .= $text;
	}
	
	public function output() {
		$this->content .= '</form>';
		
		return $this->content;
	}
	
}

?>