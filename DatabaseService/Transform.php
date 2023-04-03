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