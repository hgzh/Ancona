<?php
/**
 * == DatabaseService/Transform ==
 * Database format transformations
 *
 * (C) 2015-2023 Hgzh
 *
 */

namespace Ancona\DatabaseService;

class Transform {

	/**
	 * decodeDate()
	 * formats datetime values for database
	 *
	 * @param input input string
	 * @param time also return time value
	 */
	public static function decodeDate( $input, $time = false ) {
		if ( (int)$input === 0 ) {
			return '';
		} else {
			$input = strtotime( $input );
			if ( $time == false ) {
				return date( 'd.m.Y', $input );
			} else {
				return date( 'd.m.Y H:i', $input );
			}
		}
	}
	
	/**
	 * decodeDaytime()
	 * formats daytime values for database
	 *
	 * @param input input string
	 * @param time also return time value
	 */
	public static function decodeDaytime( $input ) {
		if ( (int)$input === 0 ) {
			return '';
		} else {
			$input = strtotime( $input );
			return date( 'H:i', $input );
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
		} elseif ( $type === 'time' ) {
			return '00:00:00';
		}
		
		return 0;
	}
	
	/**
	 * falsifyZeroValue()
	 * returns false or null if zero value or the value itself if not
	 *
	 * @param type data type
	 * @param value value to falsify
	 * @param return return value if false
	 */
	public static function falsifyZeroValue( $type, $value, $return = false) {
		
		if ( !isset( $value ) || $value === false ) {
			return $return;
		}
		
		if ( $type === 'number' ) {
			return ( (int)$value === 0 ? $return : $value );
		} elseif ( $type === 'date' ) {
			return ( $value == '0000-00-00' ? $return : $value );
		} elseif ( $type === 'datetime' ) {
			return ( $value == '0000-00-00 00:00:00' ? $return : $value );
		} elseif ( $type === 'time' ) {
			return ( $value == '00:00:00' ? $return : $value );
		}
		
		return $value;
	}
	
	/**
	 * validateDatetime()
	 * checks if the given date matches the required database format, if not, returns
	 * zero equivalent date
	 *
	 * @param type data type
	 * @param value value to validate
	 */	
	public static function validateDatetime( $type, $date ) {
		if ( $type === 'date' ) {
			$format = 'Y-m-d';
		} elseif ( $type === 'datetime' ) {
			$format = 'Y-m-d H:i:s';			
		}
    	$obj = \DateTime::createFromFormat( $format, $date );
    	if ( $obj === false || $obj->format( $format ) !== $date ) {
			if ( $type === 'date' ) {
				$return = '0000-00-00';
			} elseif ( $type === 'datetime' ) {
				$return = '0000-00-00 00:00:00';			
			}
		} else {
			$return = $obj->format( $format );
		}
		return $return;
	}
	
}
?>