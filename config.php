<?php
/**
 * == ConfigService ==
 * Ancona configuration
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\ConfigService;

require_once( 'configMessages.php' );
require_once( 'configFramework.php' );
require_once( 'configInstance.php' );

/**
 * == MESSAGE CLASS ==
 * system message
 */
class message {
	public static function get( $key, $fallback = null ) {
		return ConfigMessage::$val[ $key ] ?? $fallback;
	}
}

/**
 * == FRAMEWORK CLASS ==
 * framework configuration
 */
class framework {
	public static function get( $key, $fallback = null ) {
		return ConfigFramework::$val[ $key ] ?? $fallback;
	}
}

/**
 * == INSTANCE CLASS ==
 * instance configuration
 */
class instance {
	public static function get( $key, $fallback = null ) {
		return ConfigInstance::$val[ $key ] ?? $fallback;
	}
}

?>