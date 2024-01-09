<?php
/**
 * == HtmlService/bsForm ==
 * filter for tables or lists in Ancona
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\HtmlService;

class Filter extends bsForm {
	
	// filter name
	public $name = '';
	
	// filter target
	public $target = '';
	
	// placeholder
	public $placeholder = '';
	
	/**
	 * __construct()
	 * initializations
	 *
	 * @param name filter name
	 * @param target filter target
	 * @param placeholder placeholder text
	 */
	public function __construct( $name, $target, $placeholder) {			
		parent::__construct( 'filter-' . $name );
		
		$this->name        = 'filter-' . $name;
		$this->target      = $target;
		$this->placeholder = $placeholder;
		
		$this->createFilter();
	}
	
	/**
	 * createFilter()
	 * creates the filter html
	 */
	private function createFilter() : void {
		$this->addHTML( Html::elem(
			'span',
			[
				'class'           => 'anc-filter',
				'data-anc-target' => $this->target
			],
			'',
			false )
		);
					   
		$this->input( [
			'name'        => 'input',
			'type'        => 'text',
			'size'        => 'sm',
			'placeholder' => $this->placeholder
		] );
		
		$this->addHTML( '</span>' );
	}
}

?>