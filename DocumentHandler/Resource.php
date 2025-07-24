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
	
	public const REGION_HEAD = 'head';
	public const REGION_BODY = 'body';
	
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
	 * @param rank rank of resource
	 * @param region region of resource
	 */	
	public function addResource( Document\Resource $resource, $rank = 0, $region = false ) : void {
		if ( $region === false && $resource->getType() === Document\Resource::TYPE_JS ) {
			$region = Resource::REGION_BODY;	
		}
		
		$add = [
			'object' => $resource,
			'rank'   => $rank,
			'region' => $region
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
	 * @param region resource region
	 */		
	public function createResource( $name, $type, $source, $integrity = false, $crossorigin = 'anonymous', $rank = 0, $includeType = false, $region = false ) : void {
		$res = new Document\Resource( $name, $type );
		$res->setSource( $source )
			->setIntegrity( $integrity )
			->setCrossorigin( $crossorigin )
			->setIncludeType( $includeType );
		
		$this->addResource( $res, $rank, $region );
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
	 * @param region region of the resource
	 */		
	public function getResourcesHtmlByType( $type, $region = false ) : string {
		$html = '';
		
		// sort resources array by rank
		usort($this->resources, fn($a, $b) => $a['rank'] <=> $b['rank']);
				
		foreach ( $this->resources as $resource ) {
			if ( $resource['object']->getType() == $type && ( $region === false || $region === $resource['region'] ) ) {
				$html .= $resource['object']->getHtml();
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