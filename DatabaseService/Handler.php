<?php
/**
 * == DatabaseService/Handler ==
 * Database connection handling
 *
 * (C) 2015-2023 Hgzh
 *
 */

namespace Ancona\DatabaseService;

use Ancona\ConfigService as Config;
use Ancona\ExceptionService as Exception;

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

?>