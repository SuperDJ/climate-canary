<?php
class Sensor {
	private $_db;

	function __construct( Database $db = null ) {
		if( !is_null( $db ) ) {
			$this->_db = $db;
		}
	}

	public function add( $data, $type ) {
		$stmt = $this->_db->mysqli->prepare("INSERT INTO `$type` (`date`, `$type`) VALUES(:date, :value)");

		$count = count( $data );
		$i = 0;
		foreach( $data as $row => $field ) {
			// If date not exists add to database
			if( !$this->_db->exists('date', $type, 'date', $field['date'] ) ) {
				$stmt->bindParam( ':date', $field['date'], PDO::PARAM_STR );
				$stmt->bindParam( ':value', $field[$type], PDO::PARAM_STR );
				$stmt->execute();

				if( $stmt->rowCount() >= 1 ) {
					$i++;
				} else {
					$stmt = null;

					return false;
				}
			} else {
				$i++;
			}
		}

		$stmt = null;
		if( $count == $i ) {
			return true;
		} else {
			return false;
		}
	}
}