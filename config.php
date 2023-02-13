<?php
/**
 * ##### config.php #####
 * Ancona: Konfiguration
 *
 * (C) 2023 Hgzh
 *
 */

namespace Ancona\ConfigService;

require_once( 'configMessages.php' );
require_once( 'configFramework.php' );
require_once( 'configInstance.php' );

/**
 * ##### CLASS message CLASS #####
 * Systemnachrichten
 */
class message {
	public static function get( $key, $fallback = null ) {
		return ConfigMessage::$val[ $key ] ?? $fallback;
	}
}

/**
 * ##### CLASS framework CLASS #####
 * Framework-Konfiguration
 */
class framework {
	public static function get( $key, $fallback = null ) {
		return ConfigFramework::$val[ $key ] ?? $fallback;
	}
}

/**
 * ##### CLASS instance CLASS #####
 * eigene Instanzen-Konfiguration
 */
class instance {
	public static function get( $key, $fallback = null ) {
		return ConfigInstance::$val[ $key ] ?? $fallback;
	}
}

?>