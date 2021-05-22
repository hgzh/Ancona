<?php
/**
 * ##### database.php #####
 * hgzWeb: Datenbankfunktionen
 *
 * (C) 2015-2021 Hgzh
 *
 */

/**
 * ##### CLASS Database CLASS #####
 * Datenbankfunktionen als Erweiterung zu mysqli
 */

class Database extends mysqli {

	/**
	 * connectOwn()
	 * Verbindung mit mysqli-Datenbank herstellen.
	 *
	 * Parameter
	 * - host       : Datenbankhost
	 * - accessName : Benutzername des Zugriffskontos
	 * - accessPass : Passwort des Zugriffskontos
	 * - name       : Name der Datenbank
	 *
	 */
	public function connectOwn($host = '', $accessName = '', $accessPass = '', $name = '') {			
		// Fallback, falls nicht angegeben
		if ($host == '') { $host = 'localhost'; }
		if ($accessName == '') { $accessName = UserConfig::$var['database-user-name']; }
		if ($accessPass == '') { $accessPass = UserConfig::$var['database-user-pass']; }
		if ($name == '') { $name = UserConfig::$var['database-name']; }

		parent::connect($host, $accessName, $accessPass, $name);
		if ($this->connect_error) {
			throw new Exception('Fehler bei der Verbindung: ' . $this->connect_error);
		}
		if (!parent::set_charset('utf8')) {
			throw new Exception('Fehler beim Laden von UTF-8: ' . $this->error);
		}
	}

	/**
	 * refValues()
	 * übergibt rohe Datenwerte als Referenz
	 *
	 * Parameter
	 * - arr : Array mit Werten
	 */
	private function refValues($arr){
		if (strnatcmp(phpversion(), '5.3') >= 0) {
			$refs = [];
			foreach($arr as $k1 => $v1)
				$refs[$k1] = &$arr[$k1];
			return $refs;
		}
		return $arr;
	}

	/**
	 * executeQuery()
	 * erstellt ein prepared statement und führt daraus direkt eine Datenbankabfrage durch
	 *
	 * Parameter
	 * - (1)    : Query-String
	 * - (2)    : Referenztypen (1. Parameter von bind_param)
	 * - (3...) : Referenzen (folgende Parameter von bind_param)
	 */
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

	/**
	 * decodeDate()
	 * formatiert Datums- und Zeitangaben aus der Datenbank
	 *
	 * Parameter
	 * - input : Eingabestring
	 * - time  : auch Zeit ausgeben
	 */
	public static function decodeDate($input, $time = false) {
		if ($input > 0) {
			$input = strtotime($input);
			if ($time == false) {
				return date('d.m.Y', $input);
			} else {
				return date('d.m.Y H:i', $input);
			}
		} else {
			return '';
		}
	}

	/**
	 * decodeTime()
	 * formatiert Zeitangaben in Hundertstelsekunden aus der Datenbank im Format 00:00,00
	 *
	 * Parameter
	 * - input : Eingabestring
	 * - diff  : Vorzeichen angeben
	 */		
	public static function decodeTime($input, $diff = false) {
		if ($input < 0) {
			$input = -$input;
			$vz    = '-';
		} else {
			$vz    = '+';
		}
		
		// Berechnung
		$val = floor($input / 6000) . ':' . str_pad(floor(($input % 6000) / 100), 2, '0', STR_PAD_LEFT) . ',' . str_pad(floor($input % 100), 2, '0', STR_PAD_LEFT);
		
		// Vorzeichen anfügen
		if ($diff === true) {
			$val = $vz . $val;
		}
		
		return $val;
	}

	/** 
	 * fetchResult()
	 * bezieht Ergebnisse von mysqli_stmt und mysqli_result in der gleichen Funktion
	 *
	 * Parameter:
	 * - query : mysqli result/statement
	 */
	public static function fetchResult($query) {   
		$array = [];

		if ($query instanceof mysqli_stmt) {
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
			
		} elseif ($query instanceof mysqli_result) {
			// mysqli_result

			// Alle Zeilen beziehen
			while ($row = $query->fetch_assoc()) {
				$array[] = $row;
			}
		}

		// Rückgabe
		return $array;
	}

}
?>