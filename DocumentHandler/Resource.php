<?php
/**
 * == DocumentHandler/Resource ==
 * manage resources in Ancona
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\DocumentHandler;

use Ancona\Ancona as Ancona;
use Ancona\DocumentService as Document;

class Resource {
	
	protected $resources = [];
	
	/**
	 * __construct()
	 * initializations
	 */		
	public function __construct() {
		// autoload custom resources on init
		$this->autoloadCustomResources();
	}
	
	/**
	 * addResource()
	 * adds a resource to the resource handler
	 *
	 * @param resource resource object
	 * @param rank ranking of resource
	 */	
	public function addResource( $resource, $rank = 0 ) : void {
		$add = [
			'object' => $resource,
			'rank'   => $rank
		];
		$this->resources[] = $add;
	}
	
	/**
	 * createResource()
	 * creates a new resource object and adds it to the resource handler
	 *
	 * @param name resource name
	 * @param type resource type
	 * @param source origin file
	 * @param integrity integrity hash
	 * @param crossorigin crossorigin parameters
	 * @param rank resource rank
	 * @param includeType include type
	 */		
	public function createResource( $name, $type, $source, $integrity = false, $crossorigin = 'anonymous', $rank = 0, $includeType = false ) : void {
		$res = new Document\Resource( $name, $type );
		$res->setSource( $source )
			->setIntegrity( $integrity )
			->setCrossorigin( $crossorigin )
			->setIncludeType( $includeType );
		
		$this->addResource( $res, $rank );
	}
	
	/**
	 * deleteResource()
	 * deletes a resource from the resource handler
	 *
	 * @param name name of the resource
	 */	
	public function deleteResource( $name ) : void {
		foreach ( $this->resources as $k => $resource ) {
			if ( $resource[ 'object' ]->getName() == $name ) {
				unset( $resource[ 'object' ] );
				unset( $this->resources[ $k ] );
			}
		}
	}
	
	/**
	 * getResourcesHtmlByType()
	 * gets the html of the resources having the given type
	 *
	 * @param type type of the resource
	 */		
	public function getResourcesHtmlByType( $type ) : string {
		$html = '';
		
		// sort resources array by rank
		usort($this->resources, fn($a, $b) => $a['rank'] <=> $b['rank']);
				
		foreach ( $this->resources as $resource ) {
			if ( $resource[ 'object' ]->getType() == $type ) {
				$html .= $resource[ 'object' ]->getHtml();
			}
		}
		
		return $html;
	}
	
	public function autoloadCustomResources() {
		if ( file_exists( 'addcss.css' ) ) {
			$this->createResource(
				'anc-custom-css',
				Document\Resource::TYPE_CSS,
				Ancona::getAbs() . 'addcss.css',
				false,
				'anonymous',
				999
			);
		}
		if ( file_exists( 'addjs.js' ) ) {
			$this->createResource(
				'anc-custom-js',
				Document\Resource::TYPE_JS,
				Ancona::getAbs() . 'addjs.js',
				false,
				'anonymous',
				999
			);
		}
	}
	
}

?>