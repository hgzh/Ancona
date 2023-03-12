<?php
/**
 * == DatabaseService ==
 * Database handling
 *
 * (C) 2015-2023 Hgzh
 *
 */

namespace Ancona\DatabaseService;

use Ancona\ConfigService as Config;
use Ancona\ExceptionService as Exception;

/**
 * == HANDLER CLASS ==
 * mysqli extensions
 */
class Handler extends \mysqli {
	
	/**
	 * connectOwn()
	 * connect to mysql database
	 *
	 * @param host database host
	 * @param accessName username for access
	 * @param accessPass password for access
	 * @param name database nme
	 *
	 */
	public function connectOwn( $host = '', $accessName = '', $accessPass = '', $name = '' ) {
		// fallback if not set
		if ( $host == '' ) {
			$host = 'localhost';
		}
		if ( $accessName == '' ) {
			$accessName = Config\framework::get( 'database-user-name' );
		}
		if ( $accessPass == '' ) {
			$accessPass = Config\framework::get( 'database-user-pass' );
		}
		if ( $name == '' ) {
			$name = Config\framework::get( 'database-name' );
		}

		parent::connect( $host, $accessName, $accessPass, $name );
		if ( $this->connect_error ) {
			throw new Exception\Database( $this->connect_error, $this->connent_errno );
		}
		if ( !parent::set_charset( 'utf8' ) ) {
			throw new Exception\Database( $this->error, $this->errno );
		}
	}

	/**
	 * executeQuery()
	 * creates a prepared statement and directly executes a database query
	 *
	 * @param (1) query string
	 * @param (2) reference types (first parameter of mysqli's bind_param)
	 * @param (3...) references (following parameters of mysqli's bind_param)
	 */
	public function executeQuery() {

		// at least 1 parameter is required
		$numParam = func_num_args();
		if ( $numParam < 1 ) {
			throw new Exception\Argument( __CLASS__ . '::executeQuery()',
										  'Wrong parameter count' );
		}

		// get all parameters
		$parList = func_get_args();

		$query = $this->prepare( $parList[0] );
		if ( $query === false ) {
			throw new Exception\Database( $this->error, $this->errno );
		}

		// strip first parameter, hand the rest to bind_param
		unset( $parList[0] );

		// only, if parameters are left
		if ( count( $parList ) != 0 ) {
			call_user_func_array( [$query, 'bind_param'], $this->refValues( $parList ) );
		}

		// execute query
		$query->execute();

		// return statement object
		return $query;
	}
	
	/** 
	 * fetchResult()
	 * gets results of mysqli_stmt und mysqli_result in the same function call
	 *
	 * @param query mysqli result/statement
	 */
	public static function fetchResult( $query ) {   
		$array = [];

		if ( $query instanceof \mysqli_stmt ) {
			// mysqli_stmt

			// statement metadata
			$query->store_result();
			$variables = [];
			$data      = [];
			$meta      = $query->result_metadata();

			// sql field names
			while ( $field = $meta->fetch_field() ) {
				$variables[] = &$data[$field->name];
			}

			// bind results to field names
			call_user_func_array( [$query, 'bind_result'], $variables );
			
			// get data
			$i = 0;
			while ( $query->fetch() ) {
				$array[$i] = [];
				foreach ( $data as $k => $v )
					$array[$i][$k] = $v;
				$i++;
			}
			
		} elseif ( $query instanceof \mysqli_result ) {
			// mysqli_result

			// get all lines
			while ( $row = $query->fetch_assoc() ) {
				$array[] = $row;
			}
		}

		// return result array
		return $array;
	}
	
	/**
	 * refValues()
	 * passes raw data by reference
	 *
	 * @param arr input array
	 */
	private function refValues( $arr ){
		if ( strnatcmp( phpversion(), '5.3' ) >= 0) {
			$refs = [];
			foreach ( $arr as $k1 => $v1 )
				$refs[$k1] = &$arr[$k1];
			return $refs;
		}
		return $arr;
	}
}

/**
 * == DATABASE CLASS ==
 * deprecated functions
 */
class Database extends \mysqli {

	// DEPRECATED: Handler::connectOwn() nutzen
	public function connectOwn($host = '', $accessName = '', $accessPass = '', $name = '') {			
		// Fallback, falls nicht angegeben
		if ($host == '') { $host = 'localhost'; }
		if ($accessName == '') { $accessName = Config\framework::get('database-user-name'); }
		if ($accessPass == '') { $accessPass = Config\framework::get('database-user-pass'); }
		if ($name == '') { $name = Config\framework::get('database-name'); }

		parent::connect($host, $accessName, $accessPass, $name);
		if ($this->connect_error) {
			throw new Exception('Fehler bei der Verbindung: ' . $this->connect_error);
		}
		if (!parent::set_charset('utf8')) {
			throw new Exception('Fehler beim Laden von UTF-8: ' . $this->error);
		}
	}

	// DEPRECATED: Handler::refValues() nutzen
	private function refValues($arr){
		if (strnatcmp(phpversion(), '5.3') >= 0) {
			$refs = [];
			foreach($arr as $k1 => $v1)
				$refs[$k1] = &$arr[$k1];
			return $refs;
		}
		return $arr;
	}

	// DEPRECATED: Handler::executeQuery() nutzen
	public function executeQuery() {

		// mindestens 1 Parameter muss vorhanden sein
		$numParam = func_num_args();
		if ($numParam < 1) {
			throw new Exception('executeQuery: Falsche Anzahl von Parametern');
		}

		// alle Parameter beziehen
		$parList = func_get_args();

		$query = $this->prepare($parList[0]);
		if ($query === false) {
			throw new Exception('executeQuery: Fehler beim Erstellen des Ausdrucks: ' . $this->error);
		}

		// ersten Parameter entfernen, der Rest wird an bind_param übergeben
		unset($parList[0]);

		// Übergabe, falls noch Parameter vorhanden sind
		if (count($parList) != 0) {
			call_user_func_array([$query, 'bind_param'], $this->refValues($parList));
		}

		// Abfrage ausführen
		$query->execute();

		// Statement-Objekt zurückgeben
		return $query;
	}
	
	// DEPRECATED: Handler::fetchResult() nutzen
	public static function fetchResult($query) {   
		$array = [];

		if ($query instanceof \mysqli_stmt) {
			// mysqli_stmt

			// Statement-Metadaten
			$query->store_result();
			$variables = [];
			$data = [];
			$meta = $query->result_metadata();

			// SQL-Feldnamen
			while ($field = $meta->fetch_field()) {
				$variables[] = &$data[$field->name];
			}

			// Ergebnisse an Feldnamen binden
			call_user_func_array([$query, 'bind_result'], $variables);
			
			// Daten beziehen
			$i = 0;
			while ($query->fetch()) {
				$array[$i] = [];
				foreach ($data as $k => $v)
					$array[$i][$k] = $v;
				$i++;
			}
			
		} elseif ($query instanceof \mysqli_result) {
			// mysqli_result

			// Alle Zeilen beziehen
			while ($row = $query->fetch_assoc()) {
				$array[] = $row;
			}
		}

		// Rückgabe
		return $array;
	}	
	
	// DEPRECATED: Transform::decodeDate() nutzen
	public static function decodeDate($input, $time = false) {
		return Transform::decodeDate($input, $time);
	}

	// DEPRECATED: Transform::decodeTime() nutzen
	public static function decodeTime($input, $diff = false) {
		return Transform::decodeTime($input, $diff);
	}

	// DEPRECATED: Transform::encodeTime() nutzen
	public static function encodeTime($input) {
		return Transform::encodeTime($input);
	}	
	
}

/**
 * == TRANSFORM CLASS ==
 * data transformation for handling in the database
 */
class Transform {

	/**
	 * decodeDate()
	 * formats datetime values for database
	 *
	 * @param input input string
	 * @param time also return time value
	 */
	public static function decodeDate( $input, $time = false ) {
		if ( $input > 0 ) {
			$input = strtotime( $input );
			if ( $time == false ) {
				return date( 'd.m.Y', $input );
			} else {
				return date( 'd.m.Y H:i', $input );
			}
		} else {
			return '';
		}
	}

	/**
	 * decodeTime()
	 * formats time in milliseconds in 00:00,00 format
	 *
	 * @param input input string
	 * @param diff show signs
	 */		
	public static function decodeTime( $input, $diff = false ) {
		if ( $input < 0 ) {
			$input = -$input;
			$vz    = '-';
		} else {
			$vz    = '+';
		}
		
		// calculation
		$val = floor( $input / 6000 )
			 . ':'
			 . str_pad( floor( ($input % 6000) / 100 ), 2, '0', STR_PAD_LEFT )
			 . ','
			 . str_pad( floor( $input % 100 ), 2, '0', STR_PAD_LEFT );
		
		// add sign
		if ( $diff === true ) {
			$val = $vz . $val;
		}
		
		return $val;
	}
	
	/**
	 * encodeTime()
	 * formats strings in 00:00,00 format to milliseconds for usage in the database
	 *
	 * @param input input string
	 */			
	public static function encodeTime( $input ) {
		$parts = explode( ':', $input );
		$val   = 0;
		
		if ( count( $parts ) > 2) {
			// format: 1:12:23,45
			$val += ( intval($parts[0] ) * 60 * 60 * 100 );
			$val += ( intval( $parts[1] ) * 60 * 100 );
			$partsDec = explode( ',', $parts[2] );
		} else {
			// format: 1:12,23
			$val += ( intval( $parts[0] ) * 60 * 100 );
			$partsDec = explode( ',', $parts[1] );
		}
		$val += ( intval( $partsDec[0] ) * 100 );
		$val += ( intval( $partsDec[1] ) );
		
		return $val;
	}
	
	/**
	 * normalizeZeroValue()
	 * transforms zero values correctly
	 *
	 * @param type data type
	 * @param value value to transform
	 */
	public static function normalizeZeroValue( $type, $value ) {
		
		if ( isset( $value ) && ( (int)$value !== 0 ) ) {
			return $value;
		}
		
		if ( $type === 'number' ) {
			return 0;
		} elseif ( $type === 'date' ) {
			return '0000-00-00';
		} elseif ( $type === 'datetime' ) {
			return '0000-00-00 00:00:00';
		}
		
		return 0;
	}
	
}
?>