<?php
class Address {
	private $_db; // Store Database class

	function __construct( Database $db = null ) {
		if( !is_null( $db ) ) {
			$this->_db = $db;
		}
	}

	/**
	 * Add address to database
	 *
	 * @param array $data
	 *
	 * @return bool
	 */
	public function add( array $data ) {
		// Check if position doesn't already exists
		$stmt = $this->_db->mysqli->prepare("SELECT `id` FROM `address` WHERE `address` = :address AND `latitude` = :latitude AND `longitude` = :longitude");
		$stmt->bindParam(':address', $data['address'], PDO::PARAM_STR);
		$stmt->bindParam(':latitude', $data['latitude'], PDO::PARAM_STR);
		$stmt->bindParam(':longitude', $data['longitude'], PDO::PARAM_STR);
		$stmt->execute();

		$count = $stmt->rowCount();
		$stmt = null; // Close query
		echo $count;
		if( $count == 0 ) {
			echo 1;
			// Insert new location
			$stmt = $this->_db->mysqli->prepare( "INSERT INTO `address` (`address`, `latitude`, `longitude`) VALUES (:address, :latitude, :longitude)" );
			$stmt->bindParam( ':address', $data['address'], PDO::PARAM_STR );
			$stmt->bindParam( ':latitude', $data['latitude'], PDO::PARAM_STR );
			$stmt->bindParam( ':longitude', $data['longitude'], PDO::PARAM_STR );
			$stmt->execute();

			if( $stmt->rowCount() >= 1 ) {
				echo 2;
				$stmt = null;
				return true;
			} else {
				echo 3;
				$stmt = null;
				return false;
			}
		} else {
			echo 4;
			return true;
		}
	}

	/**
	 * Delete address from database
	 *
	 * @param $id
	 *
	 * @return bool
	 */
	public function delete( $id ) {
		$stmt = $this->_db->mysqli->prepare("DELETE FROM `address` WHERE `id` = :id");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();

		if( $stmt->rowCount() >= 1 ) {
			$stmt = null;
			return true;
		} else {
			$stmt = null;
			return false;
		}
	}

	/**
	 * Get data from address
	 *
	 * @param null $id
	 *
	 * @return bool
	 */
	public function data( $id = null ) {
		$query = "
			SELECT `a`.`id`, `address`, `name`, `latitude`, `longitude`, `icons_id`, `icon` 
			FROM `address` `a`
			JOIN `icons` `i`
				ON `i`.`id` = `a`.`icons_id`
		";

		if( !is_null( $id ) ) {
			$query .= "WHERE `a`.`id` = :id LIMIT 1";
			$stmt = $this->_db->mysqli->prepare($query);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		} else {
			$stmt = $this->_db->mysqli->prepare($query);
		}
		$stmt->execute();

		if( $stmt->rowCount() >= 1 ) {
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt = null;
			return $result;
		} else {
			$stmt = null;
			return false;
		}
	}

	/**
	 * Edit address
	 *
	 * @param $data
	 *
	 * @return bool
	 */
	public function edit( $data ) {
		$stmt = $this->_db->mysqli->prepare("UPDATE `address` SET `name` = :name, `icons_id` = :icons_id WHERE `id` = :id");
		$stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
		$stmt->bindParam(':icons_id', $data['icons_id'], PDO::PARAM_INT);
		$stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
		$stmt->execute();

		if( $stmt->rowCount() >= 1 ) {
			$stmt = null;
			return true;
		} else {
			$stmt = null;
			return false;
		}
	}

	/**
	 * Get all categories
	 *
	 * @return bool
	 */
	public function categories() {
		$stmt = $this->_db->mysqli->prepare("SELECT `id`, `icon`, `category` FROM `icons`");
		$stmt->execute();

		if( $stmt->rowCount() >= 1 ) {
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt = null;
			return $result;
		} else {
			$stmt = null;
			return false;
		}
	}

	/**
	 * Get all favorites
	 *
	 * @return bool
	 */
	public function favorites() {
		$query = "
			SELECT `a`.`id`, `address`, `name`, `latitude`, `longitude`, `icons_id`, `icon` 
			FROM `address` `a`
			JOIN `icons` `i`
				ON `i`.`id` = `a`.`icons_id`
			WHERE `icons_id` != 4	
		";
		$stmt = $this->_db->mysqli->prepare($query);
		$stmt->execute();

		if( $stmt->rowCount() >= 1 ) {
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt = null;
			return $result;
		} else {
			$stmt = null;
			return false;
		}
	}
}