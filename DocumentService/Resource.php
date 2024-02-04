<?php
/**
 * == DocumentService/Resource ==
 * external resource in Ancona
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\DocumentService;

use Ancona\HtmlService as Html;

class Resource {
	
	// resource name
	protected $name;
	
	// resource type (one of TYPE_CSS / TYPE_JS)
	protected $type;
	
	// resource origin
	protected $source;
	
	// hash of the resource
	protected $integrity;
	
	// crossorigin of the resource
	protected $crossorigin;
	
	// include type of resource
	protected $includeType;
	
	public const TYPE_CSS = 'css';
	public const TYPE_JS  = 'js';
	
	/**
	 * __construct()
	 *
	 * @param name name of the resource
	 * @param type type of the resource
	 */		
	public function __construct( $name, $type ) {
		$this->name = $name;
		$this->type = $type;
	}
	
	/**
	 * getName()
	 * returns the resource's name
	 */		
	public function getName() : string {
		return $this->name;
	}

	/**
	 * getName()
	 * returns the resource's type
	 */		
	public function getType() : string {
		return $this->type;
	}
	
	/**
	 * setSource()
	 * sets the resource's origin file
	 *
	 * @param source source file
	 */		
	public function setSource( $source ) : Resource {
		$this->source = $source;
		return $this;
	}
	
	/**
	 * getSource()
	 * returns the resource's origin file
	 */		
	public function getSource() : string {
		return $this->source;
	}	

	/**
	 * setIntegrity()
	 * sets the resource's integrity hash
	 *
	 * @param hash integrity hash
	 */		
	public function setIntegrity( $hash ) : Resource {
		$this->integrity = $hash;
		return $this;
	}
	
	/**
	 * getIntegrity()
	 * returns the resource's origin file
	 */		
	public function getIntegrity() : string {
		return $this->integrity;
	}

	/**
	 * setCrossorigin()
	 * sets the resource's crossorigin parameters
	 *
	 * @param crossorigin crossorigin parameters
	 */		
	public function setCrossorigin( $crossorigin ) : Resource {
		$this->crossorigin = $crossorigin;
		return $this;
	}
	
	/**
	 * getCrossorigin()
	 * returns the resource's origin file
	 */		
	public function getCrossorigin() : string {
		return $this->crossorigin;
	}
	
	/**
	 * setIncludeType()
	 * sets the resource's include type parameters
	 *
	 * @param includeType include type parameters
	 */		
	public function setIncludeType( $includeType ) : Resource {
		$this->includeType = $includeType;
		return $this;
	}	
	
	/**
	 * getCrossorigin()
	 * returns the resource's origin file
	 */		
	public function getIncludeType() : string {
		return $this->includeType;
	}	
	
	/**
	 * getHtml()
	 * returns the resource's html include representation
	 */		
	public function getHtml() : string {
		if ( $this->type == Resource::TYPE_CSS ) {
			$html = Html\Html::elem(
				'link',
				[
					'rel'         => 'stylesheet',
					'href'        => $this->source,
					'integrity'   => $this->integrity,
					'crossorigin' => $this->crossorigin
				],
				'',
				false
			);
		} elseif ( $this->type == Resource::TYPE_JS ) {
			$html = Html\Html::elem(
				'script',
				[
					'src'         => $this->source,
					'integrity'   => $this->integrity,
					'crossorigin' => $this->crossorigin,
					'type'        => $this->includeType
				]
			);
		}
		
		// return html
		return $html;
	}
		
}

?>