<?php
/**
 * == FormService/bsForm ==
 * bootstrap forms in Ancona
 *
 * (C) 2021-2023 Hgzh
 *
 */

namespace Ancona\FormService;

use Ancona\HtmlService as Html;
use Ancona\ExceptionService as Exception;

class bsForm extends Html {
	
	// form name
	public $name = '';
	
	/**
	 * __construct()
	 * initializations
	 *
	 * @param name form name
	 * @param action form action
	 * @param method http request method
	 * @param class additional classes
	 */
	public function __construct( $name, $action = false, $method = false, $class = false ) {			
		parent::__construct();
		
		$this->name     = $name;
		$this->content .= $this->elem(
			'form',
			[
				'id'     => 'anc-form-' . $name,
				'action' => $action,
				'method' => $method,
				'class'  => $class
			],
			'',
			false
		);
	}
	
	/**
	 * input()
	 * create form input
	 *
	 * @param a form control arguments
	 */	
	public function input( $a ) {
		// check params
		$a['float']       ??= false;
		$a['placeholder'] ??= ( isset( $a['label'] ) ? $a['label'] : '...' );
		$a['maxlength']   ??= false;
		$a['required']    ??= false;
		$a['readonly']    ??= false;
		$a['disabled']    ??= false;
		$a['value']       ??= false;
		if ( !isset( $a['name'] ) ) {
			throw new Exception\Argument( __CLASS__, 'input()', 'name= missing for input' );
		}
		if ( !isset( $a['type'] ) ) {
			throw new Exception\Argument( __CLASS__, 'input()', 'type= missing for input' );
		}
		
		// if label or text set, add enclosing element
		if ( isset( $a['label'] ) || isset( $a['text'] ) ) {
			$enclose = true;
		} else {
			$enclose = false;
		}
		
		// tag text
		$text = '';
		
		// start enclosing div
		if ( $enclose == true ) {
			$text .= $this->elem(
				'div',
				[
					'class' => ( $a['float'] == true ? 'form-floating' : '' )
						. ( isset( $a['class'] )
							? ' ' . $a['class']
							: '')
				],
				'',
				false
			);
		}
		
		// normal label
		if ( isset( $a['label'] ) && $a['float'] == false ) {
			$text .= $this->elem(
				'label',
				[
					'for'   => 'anc-' . $this->name . '-' . $a['name'],
					'class' => 'form-label'
				],
				 $a['label'] . ':',
				true
			);
		}
		
		// input
		$text .= $this->elem(
			'input',
			[
				'type'             => $a['type'],
				'id'               => 'anc-' . $this->name . '-' . $a['name'],
				'name'             => $a['name'],
				'class'            => 'form-control'
					. ( isset( $a['size'] ) 
					? ' form-control-' . $a['size']
					: '' ),
				'placeholder'      => $a['placeholder'],
				'maxlength'        => $a['maxlength'],
				'required'         => $a['required'],
				'readonly'         => $a['readonly'],
				'disabled'         => $a['disabled'],
				'value'            => $a['value'],
				'aria-describedby' => ( isset( $a['text'] ) 
					? 'anc-' . $this->name . '-' . $a['name'] . '-text' 
					: false )
			],
			'',
			false
		);
		
		// label for form-floating
		if ( isset( $a['label'] ) && $a['float'] == true ) {
			$text .= $this->elem(
				'label',
				[
					'for'   => 'anc-' . $this->name . '-' . $a['name'],
					'class' => 'form-label'
				],
				$a['label'],
				true
			);
		}
		
		// help text
		if ( isset( $a['text'] ) ) {
			$text .= $this->elem(
				'div',
				[
					'id'    => 'anc-' . $this->name . '-' . $a['name'] . '-text',
					'class' => 'form-text'
				],
				$a['text'],
				true
			);
		}
		
		// end enclosing div
		if ( $enclose == true ) {
			$text .= '</div>';
		}
		
		$this->content .= $text;
	}
	
	/**
	 * textarea()
	 * create form textarea
	 *
	 * @param a form control arguments
	 */
	public function textarea( $a ) {
		// check params
		$a['height']      ??= '10em';
		$a['float']       ??= false;
		$a['placeholder'] ??= ( isset( $a['label'] ) ? $a['label'] : '...' );
		$a['maxlength']   ??= false;
		$a['required']    ??= false;
		$a['readonly']    ??= false;
		$a['disabled']    ??= false;
		$a['value']       ??= false;
		if ( !isset( $a['name'] ) ) {
			throw new Exception\Argument( __CLASS__, 'textarea()', 'name= missing for textarea' );
		}
		
		// if label or text set, add enclosing element
		if ( isset( $a['label'] ) || isset( $a['text'] ) ) {
			$enclose = true;
		} else {
			$enclose = false;	
		}

		// tag text
		$text = '';
		
		// start enclosing div
		if ( $enclose == true ) {
			$text .= $this->elem(
				'div',
				[
					'class' => ( $a['float'] == true 
						? 'form-floating'
						: '')
						. ( isset( $a['class'] ) 
						? ' ' . $a['class']
						: '')
				],
				'',
				false
			);
		}
		
		// normal label
		if ( isset( $a['label'] ) && $a['float'] == false) {
			$text .= $this->elem(
				'label',
				[
					'for'   => 'anc-' . $this->name . '-' . $a['name'],
					'class' => 'form-label'
				],
				$a['label'] . ':',
				true
			);
		}
		
		// textarea
		$text .= $this->elem(
			'textarea',
			[
				'id'               => 'anc-' . $this->name . '-' . $a['name'],
				'name'             => $a['name'],
				'class'            => 'form-control',
				'style'            => 'height:' . $a['height'],
				'placeholder'      => $a['placeholder'],
				'maxlength'        => $a['maxlength'],
				'required'         => $a['required'],
				'readonly'         => $a['readonly'],
				'disabled'         => $a['disabled'],
				'aria-describedby' => ( isset( $a['text'] )
					? 'anc-' . $this->name . '-' . $a['name'] . '-text'
					: false)
			],
			$a['value'],
			true
		);

		// label for form-floating
		if ( isset( $a['label'] ) && $a['float'] == true ) {
			$text .= $this->elem(
				'label',
				[
					'for'   => 'anc-' . $this->name . '-' . $a['name'],
					'class' => 'form-label'
				],
				$a['label'],
				true
			);
		}
		
		// help text
		if ( isset( $a['text'] ) ) {
			$text .= $this->elem(
				'div',
				[
					'id'    => 'anc-' . $this->name . '-' . $a['name'] . '-text',
					'class' => 'form-text'
				],
				$a['text'],
				true
			);
		}
		
		// end enclosing div
		if ( $enclose == true ) {
			$text .= '</div>';
		}
		
		$this->content .= $text;
	}
	
	/**
	 * datetime()
	 * create form datetime
	 *
	 * @param a form control arguments
	 */	
	public function datetime( $a ) {
		// check params
		$a['float']       ??= false;
		$a['placeholder'] ??= ( isset( $a['label'] ) ? $a['label'] : '...' );
		$a['required']    ??= false;
		$a['readonly']    ??= false;
		$a['disabled']    ??= false;
		$a['value']       ??= false;
		if ( !isset( $a['name'] ) ) {
			throw new Exception\Argument( __CLASS__, 'datetime()', 'name= missing for datetime' );
		}
		if ( !isset( $a['type'] ) ) {
			throw new Exception\Argument( __CLASS__, 'datetime()', 'type= missing for datetime' );
		}
		
		// if label or text set, add enclosing element
		if ( isset($a['label'] ) || isset( $a['text'] ) ) {
			$enclose = true;
		} else {
			$enclose = false;
		}
		
		// tag text
		$text = '';
		
		// start enclosing div
		if ( $enclose == true ) {
			$text .= $this->elem(
				'div',
				[
					'class' => ( $a['float'] == true
						? 'form-floating'
						: '' )
						. ( isset( $a['class'] )
						? ' ' . $a['class']
						: '' )
				],
				'',
				false
			);
		}

		// normal label
		if ( isset( $a['label'] ) && $a['float'] == false ) {
			$text .= $this->elem(
				'label',
				[
					'for'   => 'anc-' . $this->name . '-' . $a['name'],
					'class' => 'form-label'
				],
				$a['label'] . ':',
				true
			);
		}
		
		// input
		$text .= $this->elem(
			'input',
			[
				'type'             => 'text',
				'id'               => 'anc-' . $this->name . '-' . $a['name'],
				'name'             => $a['name'],
				'class'            => 'form-control datetimepicker-input anc-date-' . $a['type'],
				'data-target'      => '#' . 'anc-' . $this->name . '-' . $a['name'],
				'placeholder'      => $a['placeholder'],
				'required'         => $a['required'],
				'readonly'         => $a['readonly'],
				'disabled'         => $a['disabled'],
				'value'            => $a['value'],
				'aria-describedby' => ( isset( $a['text'] )
					? 'anc-' . $this->name . '-' . $a['name'] . '-text'
					: false )
			],
			'',
			false
		);

		// label for form-floating
		if ( isset( $a['label'] ) && $a['float'] == true ) {
			$text .= $this->elem(
				'label',
				[
					'for'   => 'anc-' . $this->name . '-' . $a['name'],
					'class' => 'form-label'
				],
				$a['label'],
				true
			);
		}
		
		// help text
		if ( isset( $a['text'] ) ) {
			$text .= $this->elem(
				'div',
				[
					'id'    => 'anc-' . $this->name . '-' . $a['name'] . '-text',
					'class' => 'form-text'
				],
				$a['text'],
				true
			);
		}
		
		// end enclosing div
		if ( $enclose == true ) {
			$text .= '</div>';
		}
		
		$this->content .= $text;
	}
	
	/**
	 * select()
	 * create form select
	 *
	 * @param a form control arguments
	 */		
	public function select( $a ) {
		// check params
		$a['float']    ??= false;
		$a['disabled'] ??= false;
		if ( !isset( $a['name'] ) ) {
			throw new Exception\Argument( __CLASS__, 'select()', 'name= missing for select' );
		}
		
		// if label or text set, add enclosing element
		if ( isset( $a['label'] ) || isset( $a['text'] ) ) {
			$enclose = true;
		} else {
			$enclose = false;
		}

		// tag text
		$text = '';		
		
		// start enclosig div
		if ( $enclose == true ) {
			$text .= $this->elem(
				'div',
				[
					'class' => ( $a['float'] == true
						? 'form-floating'
						: '' )
						. ( isset($a['class'] )
						? ' ' . $a['class']
						: '' )
				],
				'',
				false
			);
		}
		
		// normal label
		if ( isset( $a['label'] ) && $a['float'] == false ) {
			$text .= $this->elem(
				'label',
				[
					'for'   => 'anc-' . $this->name . '-' . $a['name'],
					'class' => 'form-label'
				],
				$a['label'] . ':',
				true
			);
		}
		
		// select
		$text .= $this->elem(
			'select',
			[
				'id'               => 'anc-' . $this->name . '-' . $a['name'],
				'name'             => $a['name'],
				'class'            => 'form-control'
					. ( isset( $a['size'] )
					? ' form-control-' . $a['size']
					: '' ),
				'disabled'         => $a['disabled'],
				'aria-describedby' => ( isset($a['text'] )
					? 'anc-' . $this->name . '-' . $a['name'] . '-text'
					: false )
			],
			'',
			false
		);
		
		// options
		if ( isset( $a['*'] ) ) {
			foreach ( $a['*'] as $v ) {
				// check params
				if ( !isset( $v['selected'] ) ) {
					$v['selected'] = false;
				}
				
				// create options
				$text .= $this->elem(
					'option',
					[
						'value'    => $v['value'],
						'selected' => $v['selected']
					],
					$v['text'],
					true
				);
			}
		}
		
		// close select
		$text .= '</select>';
		
		// label for form-floating
		if ( isset( $a['label'] ) && $a['float'] == true ) {
			$text .= $this->elem(
				'label',
				[
					'for'   => 'anc-' . $this->name . '-' . $a['name'],
					'class' => 'form-label'
				],
				$a['label'],
				true
			);
		}
		
		// help text
		if ( isset( $a['text'] ) ) {
			$text .= $this->elem(
				'div',
				[
					'id'    => 'anc-' . $this->name . '-' . $a['name'] . '-text',
					'class' => 'form-text'
				],
				$a['text'],
				true
			);
		}
		
		// end enclosing div
		if ( $enclose == true ) {
			$text .= '</div>';
		}
		
		$this->content .= $text;
	}
	
	/**
	 * checkbox()
	 * create form checkbox
	 *
	 * @param a form control arguments
	 */			
	public function checkbox( $a ) {
		// check params
		$a['float']    ??= false;
		$a['inline']   ??= false;
		$a['disabled'] ??= false;
		$a['checked']  ??= false;
		$a['value']    ??= false;
		if ( !isset( $a['name'] ) ) {
			throw new Exception\Argument( __CLASS__, 'checkbox()', 'name= missing for checkbox' );
		}
		if ( !isset( $a['type'] ) ) {
			throw new Exception\Argument( __CLASS__, 'checkbox()', 'type= missing for checkbox' );
		}

		// if label or text set, add enclosing element
		if ( isset( $a['label'] ) ) {
			$enclose = true;
		} else {
			$enclose = false;
		}

		// tag text
		$text = '';		
		
		// start enclosing div
		if ($enclose == true) {
			$text .= $this->elem(
				'div',
				[
					'class' => 'form-check'
						. ( $a['inline'] == true
						? ' form-check-inline'
						: '' )
						. ( isset( $a['class'] )
						? ' ' . $a['class']
						: '' )
				],
				'',
				false
			);
		}

		// checkbox
		$text .= $this->elem(
			'input',
			[
				'type'             => $a['type'],
				'id'               => 'anc-' . $this->name . '-' . $a['name'],
				'name'             => $a['name'],
				'class'            => 'form-check-input'
					. ( isset($a['inpclass'] )
					? ' ' . $a['inpclass']
					: '' ),
				'disabled'         => $a['disabled'],
				'checked'          => $a['checked'],
				'value'            => $a['value'],
				'aria-describedby' => ( isset($a['text'] )
					? 'anc-' . $this->name . '-' . $a['name'] . '-text'
					: false )
			],
			'',
			false
		);
		
		// label
		if ( isset($a['label'])) {
			$text .= $this->elem(
				'label',
				[
					'for'   => 'anc-' . $this->name . '-' . $a['name'],
					'class' => 'form-check-label'
				],
				$a['label'],
				true
			);
		}
		
		// end enclosing div
		if ( $enclose == true ) {
			$text .= '</div>';
		}

		$this->content .= $text;
	}
	
	/**
	 * button()
	 * create form button
	 *
	 * @param a form control arguments
	 */			
	public function button($a) {
		// tag text
		$text = '';		
		
		// open button group if necessary
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

			$text .= $this->elem(
				'div',
				[
					'class' => $a['group']['vertical']
						. $a['group']['size']
						. $a['group']['grid']
						. ' ' . $a['group']['class']
				],
				'',
				false
			);
		}

		foreach ( $a['*'] as $b ) {			
			// check params
			$b['disabled'] ??= false;
			$b['value']    ??= false;
			$b['name']     ??= false;
			if ( !isset( $b['type'] ) ) {
				throw new Exception\Argument( __CLASS__, 'button()', 'type= missing for button' );
			}
						
			// insert input
			$text .= $this->elem(
				'button',
				[
					'type'     => $b['type'],
					'id'       => 'anc-' . $this->name . '-'
						. ( $b['name'] == false 
						? $b['type']
						: $b['name'] ),
					'name'     => $b['name'],
					'class'    => 'btn'
						. ( isset( $b['color'] )
						? ' btn-' . $b['color']
						: '' )
						. ( isset( $b['class'] )
						? ' ' . $b['class']
						: '' ),
					'disabled' => $b['disabled'],
					'value'    => $b['value']
				],
				$b['text'],
				true
			);
		}

		// close button group
		if ( isset( $a['group'] ) ) {
			$text .= '</div>';
		}

		$this->content .= $text;
	}
	
	/**
	 * output()
	 * ouput form html
	 */			
	public function output() : string {
		$this->content .= '</form>';
		
		return $this->content;
	}
	
}

?>