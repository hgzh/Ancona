<?php
/**
 * ##### form.php #####
 * hgzWeb: Bootstrap-Form-Framework
 *
 * (C) 2021 Hgzh
 *
 */

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
	public function __construct($name, $action = false, $method = false, $class = false) {			
		parent::__construct();
		
		$this->name     = $name;
		$this->content .= $this->elem('form', ['id'     => 'hgzform-' . $name,
											   'action' => $action,
											   'method' => $method,
											   'class'  => $class
											  ],
									  '',
									  false);
	}
	
	public function input($a) {
		// Parameter prüfen
		if (!isset($a['float']))       $a['float']       = false;
		if (!isset($a['placeholder'])) $a['placeholder'] = (isset($a['label']) ? $a['label'] : '...');
		if (!isset($a['maxlength']))   $a['maxlength']   = false;
		if (!isset($a['required']))    $a['required']    = false;
		if (!isset($a['readonly']))    $a['readonly']    = false;
		if (!isset($a['disabled']))    $a['disabled']    = false;
		if (!isset($a['value']))       $a['value']       = false;
		if (!isset($a['name']))        return 'Fehler: name= fehlt für input';
		if (!isset($a['type']))        return 'Fehler: type= fehlt für input';
		
		// wenn label oder text, dann umschließendes Element
		if (isset($a['label']) || isset($a['text'])) {
			$enclose = true;
		}
		
		// umschließendes div beginnen
		if ($enclose == true) {
			$text .= $this->elem('div', ['class' => ($a['float'] == true ? 'form-floating' : '') . (isset($a['class']) ? ' ' . $a['class'] : '')
										 ],
								 '',
								 false);
		}
		
		// Label (normal)
		if (isset($a['label']) && $a['float'] == false) {
			$text .= $this->elem('label', ['for'   => 'lsm-' . $this->name . '-' . $a['name'],
										   'class' => 'form-label'
										   ],
								 $a['label'] . ':',
								 true);
		}
		
		// Input
		$text .= $this->elem('input', ['type'             => $a['type'],
									   'id'               => 'lsm-' . $this->name . '-' . $a['name'],
									   'name'             => $a['name'],
									   'class'            => 'form-control' . (isset($a['size']) ? ' form-control-' . $a['size'] : ''),
									   'placeholder'      => $a['placeholder'],
									   'maxlength'        => $a['maxlength'],
									   'required'         => $a['required'],
									   'readonly'         => $a['readonly'],
									   'disabled'         => $a['disabled'],
									   'value'            => $a['value'],
									   'aria-describedby' => (isset($a['text']) ? 'lsm-' . $this->name . '-' . $a['name'] . '-text' : false)
									   ],
							 '',
							 false);
		
		// Label für form-floating
		if (isset($a['label']) && $a['float'] == true) {
			$text .= $this->elem('label', ['for'   => 'lsm-' . $this->name . '-' . $a['name'],
										   'class' => 'form-label'
										   ],
								 $a['label'],
								 true);
		}
		
		// Hilfetext
		if (isset($a['text'])) {
			$text .= $this->elem('div', ['id' => 'lsm-' . $this->name . '-' . $a['name'] . '-text',
										 'class' => 'form-text'
										 ],
								 $a['text'],
								 true);
		}
		
		// umschließendes div beenden
		if ($enclose == true) {
			$text .= '</div>';
		}
		
		$this->content .= $text;
	}
	
	public function textarea($a) {
		// Parameter prüfen
		if (!isset($a['height']))      $a['height']      = '10em';
		if (!isset($a['float']))       $a['float']       = false;
		if (!isset($a['placeholder'])) $a['placeholder'] = (isset($a['label']) ? $a['label'] : '...');
		if (!isset($a['maxlength']))   $a['maxlength']   = false;
		if (!isset($a['required']))    $a['required']    = false;
		if (!isset($a['readonly']))    $a['readonly']    = false;
		if (!isset($a['disabled']))    $a['disabled']    = false;
		if (!isset($a['value']))       $a['value']       = false;
		if (!isset($a['name']))        return 'Fehler: name= fehlt für textarea';
		
		// wenn label oder text, dann umschließendes Element
		if (isset($a['label']) || isset($a['text'])) {
			$enclose = true;
		}
		
		// umschließendes div beginnen
		if ($enclose == true) {
			$text .= $this->elem('div', ['class' => ($a['float'] == true ? 'form-floating' : '') . (isset($a['class']) ? ' ' . $a['class'] : '')
										 ],
								 '',
								 false);
		}
		
		// Label (normal)
		if (isset($a['label']) && $a['float'] == false) {
			$text .= $this->elem('label', ['for'   => 'lsm-' . $this->name . '-' . $a['name'],
										   'class' => 'form-label'
										   ],
								 $a['label'] . ':',
								 true);
		}
		
		// Textarea
		$text .= $this->elem('textarea', ['id'               => 'lsm-' . $this->name . '-' . $a['name'],
										  'name'             => $a['name'],
										  'class'            => 'form-control',
										  'style'            => 'height:' . $a['height'],
										  'placeholder'      => $a['placeholder'],
										  'maxlength'        => $a['maxlength'],
										  'required'         => $a['required'],
										  'readonly'         => $a['readonly'],
										  'disabled'         => $a['disabled'],
										  'aria-describedby' => (isset($a['text']) ? 'lsm-' . $this->name . '-' . $a['name'] . '-text' : false)
										 ],
							 $a['value'],
							 true);

		// Label für form-floating
		if (isset($a['label']) && $a['float'] == true) {
			$text .= $this->elem('label', ['for'   => 'lsm-' . $this->name . '-' . $a['name'],
										   'class' => 'form-label'
										   ],
								 $a['label'],
								 true);
		}
		
		// Hilfetext
		if (isset($a['text'])) {
			$text .= $this->elem('div', ['id' => 'lsm-' . $this->name . '-' . $a['name'] . '-text',
										 'class' => 'form-text'
										 ],
								 $a['text'],
								 true);
		}
		
		// umschließendes div beenden
		if ($enclose == true) {
			$text .= '</div>';
		}
		
		$this->content .= $text;
	}
	
	public function datetime($a) {
		// Parameter prüfen
		if (!isset($a['float']))       $a['float']       = false;
		if (!isset($a['placeholder'])) $a['placeholder'] = (isset($a['label']) ? $a['label'] : '...');
		if (!isset($a['required']))    $a['required']    = false;
		if (!isset($a['readonly']))    $a['readonly']    = false;
		if (!isset($a['disabled']))    $a['disabled']    = false;
		if (!isset($a['value']))       $a['value']       = false;
		if (!isset($a['name']))        return 'Fehler: name= fehlt für datetime';
		if (!isset($a['type']))        return 'Fehler: type= fehlt für datetime';
		
		// wenn label oder text, dann umschließendes Element
		if (isset($a['label']) || isset($a['text'])) {
			$enclose = true;
		}
		
		// umschließendes div beginnen
		if ($enclose == true) {
			$text .= $this->elem('div', ['class' => ($a['float'] == true ? 'form-floating' : '') . (isset($a['class']) ? ' ' . $a['class'] : '')
										 ],
								 '',
								 false);
		}

		// Label (normal)
		if (isset($a['label']) && $a['float'] == false) {
			$text .= $this->elem('label', ['for'   => 'lsm-' . $this->name . '-' . $a['name'],
										   'class' => 'form-label'
										   ],
								 $a['label'] . ':',
								 true);
		}
		
		// Input
		$text .= $this->elem('input', ['type'             => 'text',
									   'id'               => 'lsm-' . $this->name . '-' . $a['name'],
									   'name'             => $a['name'],
									   'class'            => 'form-control datetimepicker-input lsm-date-' . $a['type'],
									   'data-target'      => '#' . 'lsm-' . $this->name . '-' . $a['name'],
									   'placeholder'      => $a['placeholder'],
									   'required'         => $a['required'],
									   'readonly'         => $a['readonly'],
									   'disabled'         => $a['disabled'],
									   'value'            => $a['value'],
									   'aria-describedby' => (isset($a['text']) ? 'lsm-' . $this->name . '-' . $a['name'] . '-text' : false)
									   ],
							 '',
							 false);

		// Label für form-floating
		if (isset($a['label']) && $a['float'] == true) {
			$text .= $this->elem('label', ['for'   => 'lsm-' . $this->name . '-' . $a['name'],
										   'class' => 'form-label'
										   ],
								 $a['label'],
								 true);
		}
		
		// Hilfetext
		if (isset($a['text'])) {
			$text .= $this->elem('div', ['id' => 'lsm-' . $this->name . '-' . $a['name'] . '-text',
										 'class' => 'form-text'
										 ],
								 $a['text'],
								 true);
		}
		
		// umschließendes div beenden
		if ($enclose == true) {
			$text .= '</div>';
		}
		
		$this->content .= $text;
	}
	
	public function select($a) {
		// Parameter prüfen
		if (!isset($a['float']))    $a['float']    = false;
		if (!isset($a['disabled'])) $a['disabled'] = false;
		if (!isset($a['name']))     return 'Fehler: name= fehlt für input';
		
		// wenn label oder text, dann umschließendes Element
		if (isset($a['label']) || isset($a['text'])) {
			$enclose = true;
		}
		
		// umschließendes div beginnen
		if ($enclose == true) {
			$text .= $this->elem('div', ['class' => ($a['float'] == true ? 'form-floating' : '') . (isset($a['class']) ? ' ' . $a['class'] : '')
										 ],
								 '',
								 false);
		}
		
		// Label (normal)
		if (isset($a['label']) && $a['float'] == false) {
			$text .= $this->elem('label', ['for'   => 'lsm-' . $this->name . '-' . $a['name'],
										   'class' => 'form-label'
										   ],
								 $a['label'] . ':',
								 true);
		}
		
		// Select
		$text .= $this->elem('select', ['id'               => 'lsm-' . $this->name . '-' . $a['name'],
										'name'             => $a['name'],
										'class'            => 'form-control' . (isset($a['size']) ? ' form-control-' . $a['size'] : ''),
										'disabled'         => $a['disabled'],
										'aria-describedby' => (isset($a['text']) ? 'lsm-' . $this->name . '-' . $a['name'] . '-text' : false)
									   ],
							 '',
							 false);
		
		// Options
		if (isset($a['*'])) {
			foreach ($a['*'] as $v) {
				// Parameter prüfen
				if (!isset($v['selected'])) $v['selected'] = false;
				
				// Option anlegen
				$text .= $this->elem('option', ['value'    => $v['value'],
												'selected' => $v['selected']
												],
									 $v['text'],
									 true);
			}
		}
		
		// Select schließen
		$text .= '</select>';
		
		// Label für form-floating
		if (isset($a['label']) && $a['float'] == true) {
			$text .= $this->elem('label', ['for'   => 'lsm-' . $this->name . '-' . $a['name'],
										   'class' => 'form-label'
										   ],
								 $a['label'],
								 true);
		}
		
		// Hilfetext
		if (isset($a['text'])) {
			$text .= $this->elem('div', ['id' => 'lsm-' . $this->name . '-' . $a['name'] . '-text',
										 'class' => 'form-text'
										 ],
								 $a['text'],
								 true);
		}
		
		// umschließendes div beenden
		if ($enclose == true) {
			$text .= '</div>';
		}
		
		$this->content .= $text;
	}
	
	public function checkbox($a) {
		// Parameter prüfen
		if (!isset($a['float']))    $a['float']    = false;
		if (!isset($a['disabled'])) $a['disabled'] = false;
		if (!isset($a['checked']))  $a['checked']  = false;
		if (!isset($a['value']))    $a['value']    = false;
		if (!isset($a['name']))     return 'Fehler: name= fehlt für checkbox';
		if (!isset($a['type']))     return 'Fehler: type= fehlt für checkbox';

		// wenn label oder text, dann umschließendes Element
		if (isset($a['label'])) {
			$enclose = true;
		}
		
		// umschließendes div beginnen
		if ($enclose == true) {
			$text .= $this->elem('div', ['class' => 'form-check' . ($a['inline'] == true ? ' form-check-inline' : '') . (isset($a['class']) ? ' ' . $a['class'] : '')
										],
								 '',
								 false);
		}

		// Checkbox
		$text .= $this->elem('input', ['type'             => $a['type'],
									   'id'               => 'lsm-' . $this->name . '-' . $a['name'],
									   'name'             => $a['name'],
									   'class'            => 'form-check-input' . (isset($a['inpclass']) ? ' ' . $a['inpclass'] : ''),
									   'disabled'         => $a['disabled'],
									   'checked'          => $a['checked'],
									   'value'            => $a['value'],
									   'aria-describedby' => (isset($a['text']) ? 'lsm-' . $this->name . '-' . $a['name'] . '-text' : false)
									   ],
							 '',
							 false);
		
		// Label
		if (isset($a['label'])) {
			$text .= $this->elem('label', ['for'   => 'lsm-' . $this->name . '-' . $a['name'],
										   'class' => 'form-check-label'
										   ],
								 $a['label'],
								 true);
		}
		
		// umschließendes div beenden
		if ($enclose == true) {
			$text .= '</div>';
		}

		$this->content .= $text;
	}
	
	public function button($a) {
		// Button-Group öffnen, falls nötig
		if (isset($a['group'])) {
			// size
			if (isset($a['group']['size'])) {
				$a['group']['size'] = ' btn-group-' . $a['group']['size'];
			} else {
				$a['group']['size'] = '';
			}

			// vertical
			if (isset($a['group']['vertical'])) {
				$a['group']['vertical'] = 'btn-group-vertical';
			} else {
				$a['group']['vertical'] = 'btn-group';				
			}

			// grid
			if (isset($a['group']['grid'])) {
				$a['group']['grid'] = ' ' . $a['group']['grid'];
			} else {
				$a['group']['grid'] = '';
			}

			$text .= $this->elem('div', ['class' => $a['group']['vertical'] . $a['group']['size'] . $a['group']['grid'] . ' ' . $a['group']['class']
										 ],
								 '',
								 false);
		}

		foreach ($a['*'] as $b) {			
			// Parameter prüfen
			if (!isset($b['disabled'])) $b['disabled'] = false;
			if (!isset($b['value']))    $b['value']    = false;
			if (!isset($b['name']))     $b['name']     = false;
			if (!isset($b['type']))     return 'Fehler: type= fehlt für button';
						
			// Input einfügen
			$text .= $this->elem('button', ['type'     => $b['type'],
											'id'       => 'lsm-' . $this->name . '-' . ($b['name'] == false ? 'submit' : $b['name']),
											'name'     => $b['name'],
											'class'    => 'btn' . (isset($b['color']) ? ' btn-' . $b['color'] : '') . (isset($b['class']) ? ' ' . $b['class'] : ''),
											'disabled' => $b['disabled'],
											'value'    => $b['value']
											],
								 $b['text'],
								 true);
		}

		// Button-Group wieder schließen
		if (isset($a['group'])) {
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