<?php
class Sensor {
	private $_db;

	function __construct( Database $db = null ) {
		if( !is_null( $db ) ) {
			$this->_db = $db;
		}
	}

	/**
	 * Add data from CSV
	 *
	 * @param $data
	 * @param $type
	 *
	 * @return bool
	 */
	public function add( $data, $type ) {
		$stmt = $this->_db->mysqli->prepare("INSERT INTO `$type` (`date`, `$type`) VALUES(:date, :value)");

		$count = count( $data );
		$i = 0;
		foreach( $data as $field ) {
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

	/**
	 * Get last value from database
	 *
	 * @param $type
	 *
	 * @return bool
	 */
	public function get( $type ) {
		//$array = $this->getId($type);
		//$id = $array[ array_rand( $array ) ];
		$stmt = $this->_db->mysqli->prepare("SELECT `$type` FROM `$type` ORDER BY `date` DESC LIMIT 1");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();

		if( $stmt->rowCount() >= 1 ) {
			$result = $stmt->fetch()[0];
			$stmt = null;
			return $result;
		} else {
			$stmt = null;
			return false;
		}
	}

	/**
	 * Insert data from API
	 *
	 * @param $humidity
	 * @param $humidityDate
	 * @param $co2
	 * @param $co2Date
	 * @param $temp
	 * @param $tempDate
	 *
	 * @return bool
	 */
	public function addData( $humidity, $humidityDate, $co2, $co2Date, $temp, $tempDate ) {
		$q = 0; // Store successful queries

		// Only add new humidity if date doesn't exists
		if( !$this->_db->exists('date', 'humidity', 'date', $humidityDate) ) {
			$humidity = substr_replace( (string)$humidity, '.', 2, 0 ); // Add point to string

			$stmt = $this->_db->mysqli->prepare( "INSERT INTO `humidity` (`date`, `humidity`) VALUES (:date, :humidity)" );
			$stmt->bindParam( ':date', $humidityDate, PDO::PARAM_STR );
			$stmt->bindParam( ':humidity', $humidity, PDO::PARAM_STR );
			$stmt->execute();

			if( $stmt->rowCount() >= 1 ) {
				$q++;
			}

			$stmt = null;
		} else {
			$q++;
		}

		// Only add new temperature if date doesn't exists
		if( !$this->_db->exists('date', 'degrees', 'date', $tempDate) ) {
			$temp = substr_replace( (string)$temp, '.', 2, 0 ); // Add point to string

			$stmt = $this->_db->mysqli->prepare( "INSERT INTO `degrees` (`date`, `degrees`) VALUES (:date, :degrees)" );
			$stmt->bindParam( ':date', $tempDate, PDO::PARAM_STR );
			$stmt->bindParam( ':degrees', $temp, PDO::PARAM_STR );
			$stmt->execute();

			if( $stmt->rowCount() >= 1 ) {
				$q++;
			}

			$stmt = null;
		} else {
			$q++;
		}

		// Only add new co2 if date doesn't exists
		if( !$this->_db->exists('date', 'co', 'date', $co2Date) ) {
			$stmt = $this->_db->mysqli->prepare( "INSERT INTO `co` (`date`, `co`) VALUES (:date, :co)" );
			$stmt->bindParam( ':date', $co2Date, PDO::PARAM_STR );
			$stmt->bindParam( ':co', $co2, PDO::PARAM_STR );
			$stmt->execute();

			if( $stmt->rowCount() >= 1 ) {
				$q++;
			}

			$stmt = null;
		} else {
			$q++;
		}

		if( $q == 3 ) {
			return true;
		} else {
			return false;
		}

	}
}