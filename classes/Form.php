<?php
class Form extends Database {
	public 	$errors = array(), // Storing all errors
			$return = array(), // Storing all fields and value to be used in database
			$remember = array(); // Storing all fields and value to be used in form

	private $_page, // Store current page
			$_db; // Set Database class

	function __construct( Database $db = null ) {
		if( !is_null( $db ) ) {
			$this->_db = $db;
		}

		// Delete form session to prevent errors in other forms
		$_SESSION['page'] = $_SERVER['PHP_SELF'];

		if( $this->_page !== $_SESSION['page'] ) {
			unset( $_SESSION['form'] );
		}
		$this->_page = $_SESSION['page'];
	}

	/**
	 * Validate user input
	 *
	 * @param array    $source
	 * @param  array   $items All form fields
	 * @param   null   $id    When updating
	 * @param bool     $html  True or false depending if you want to allow html input
	 *
	 * @return array|bool array          Return save data in array or error messages
	 * @internal param $ type  $source $_POST or $_GET
	 * @internal param $ type  $id     (Optional) Used to check field value with value from database
	 */
	public function check( array $source, array $items, $id = null, $html = false ) {
		if( !is_array( $items ) ) {
			return false;
		}

		foreach( $items as $item => $rules ) {
			foreach ( $rules as $rule => $rule_value ) {
				if( isset( $source[$item] ) ) {
					// Remove malicious characters
					if( !empty( $source[$item] ) ) {
						$value = $this->_db->sanitize( $source[$item], $html );
					} else {
						$value = '';
					}
				} else {
					$value = '';
				}

				switch( $rule ) {
					// Check of empty value
					case 'required':
						if( empty( $value ) ) {
							$this->addError( $rules['name'].' is leeg');
						}
						break;
					// Validate email and make sure its in lower case
					case 'email':
						if( !filter_var( $value, FILTER_VALIDATE_EMAIL ) && !preg_match( '^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$^', $value ) ) {
							$this->addError($value.' is geen geldig email adres');
						} else {
							$source[$item] = strtolower( $value );
						}
						break;
					// Validate for numeric value
					case 'numeric':
						if( !empty( $rules['required'] ) && $rules['required'] == true ) {
							if( !is_numeric( $value ) ) {
								$this->addError( $rules['name'].' moet een nummer zijn');
							}
						} else if( !empty( $value ) ) {
							if( !is_numeric( $value ) ) {
								$this->addError( $rules['name'].' moet een nummer zijn');
							}
						}
						break;
					// Maximum length
					case 'maxLength':
						if( mb_strlen( $value ) > $rule_value ) {
							$this->addError($rules['name'].' heeft een maximum van '.$rule_value.' karakters');
						}
						break;
					// Minimal length
					case 'minLength':
						if( !empty( $rules['required'] ) && $rules['required'] == true ) {
							if( mb_strlen( $value ) < $rule_value ) {
								$this->addError($rules['name'].' heeft een minimum van '.$rule_value.' karakters');
							}
						} else if( !empty( $value ) ) {
							if( mb_strlen( $value ) < $rule_value ) {
								$this->addError($rules['name'].' heeft een minimum van '.$rule_value.' karakters');
							}
						}
						break;
					// Unique in database
					// For example used to check if a username, email etc is changed for a user
					case 'unique':
						if( !is_null( $id ) && !empty( $value ) ) {
							// Get the current value
							$current_value = $this->_db->detail($item, $rule_value, 'id', $id);
							// Check if the current value is not equal to the the value
							if( $current_value != $value ) {
								// Check if the value is unique in the database
								if( $this->_db->exists($item, $rule_value, $item, $value) ) {
									if( is_numeric( $value ) ) {
										$value = $this->_db->detail( 'category', explode( '_', $item )[0], 'id', $value );
									}
									$this->addError($rules['name'].' '.$value.' bestaat al');
								}
							}
						} else {
							if( $this->_db->exists($item, $rule_value, $item, $value) && !empty( $value ) ) {
								$this->addError($rules['name'].' '.$value.' bestaat al');
							}
						}
						break;
					// Check against other value
					case 'matches':
						if( $value != $source[$rule_value] ) {
							$this->addError($rules['name'].' is niet gelijk aan '.$rule_value);
						}
						break;
					// Check if something already exists
					case 'exists':
						// If $value is numeric most likely it's an id
						if( is_numeric( $value ) ) {
							if( !$this->_db->exists($item, $rule_value, 'id', $value) ) {
								$this->addError($rules['name'].' '.$value.' bestaat niet');
							}
						} else {
							if( !$this->_db->exists($item, $rule_value, $item, $value ) ) {
								$this->addError($rules['name'].' '.$value.' bestaat niet');
							}
						}
						break;
					// base64 encode
					case 'base64':
						$source[$item] = base64_endcode( $value );
						break;
					// base64 decode
					case 'base64_decode':
						$source[$item] = base64_decode( $value );
						break;
					// md5
					case 'md5':
						$source[$item] = md5( $value );
						break;
					//sha512
					case 'sha512':
						$source[$item] = hash( 'sha512', $value );
						break;
					// sha1
					case 'sha1':
						$source[$item] = sha1( $value );
						break;
					// Remember entered data
					case 'remember':
						if( !empty( $value ) ) {
							$this->remember[$item] = $value;
						}
						break;
					// Check if a date is actually a date
					case 'date':
						if( !empty( $value ) ) {
							if( !preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $value) ) {
								$this->addError($rules['name'].' heeft geen geldige datum '.$value);
							}
						}
						break;
					// Check if time has some sort of correct notation
					case 'time':
						if( !empty( $value ) ) {
							if( !preg_match('/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/', $value) ) {
								$this->addError($rules['name'].' heeft geen geldige tijd');
							}
						}
						break;
					// Field must be empty (to prevent bots from entering text)
					case 'captcha':
						if( !empty( $value ) ) {
							$this->addError('Are you a bot?');
						}
					// Capitalize first letter
					case 'capitalize':
						$source[$item] = ucfirst( $value );
						break;
					case 'checkbox':
						if( !empty( $source[$item] ) && $source[$item] == 'on' ) {
							$source[$item] = 1;
						} else {
							$source[$item] = 0;
						}
						break;
					case 'latitude':
						if( !preg_match( '/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/' ) ) {
							$this->addError($rules['name'].' is geen geldige latitude');
						}
						break;
					case 'longitude':
						if( !preg_match( '/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/' ) ) {
							$this->addError($rules['name'].' is geen gelidge longitude');
						}
				}
				$this->return[$item] = $value;
			}
		}

		if( !is_null( $id ) ) {
			$this->return['id'] = (int)$id;
		}

		if( empty( $this->errors ) ) {
			unset( $_SESSION['form'] ); // Delete session
			return $this->return;
		} else {
			$_SESSION['form'] = $this->remember; // Storing the fields and values
			return false;
		}
	}
	/**
	 * Add error messages to array
	 * @param string $error The error message
	 */
	private function addError( $error ) {
		$this->errors[] = $error;
	}

	/**
	 * Output all error message in list
	 * @return string Error messages
	 */
	public function outputErrors() {
		$html = '';
		if( !empty( $this->errors ) ) {
			$html .= '	<div class="error sc-card sc-card-supporting" role="error">
							<ul>';
			foreach( $this->errors as $error ) {
				$html .= '		<li>' . $error . '</li>';
			}
			$html .= '		</ul>
						</div>';
		}
		return $html;
	}

	/**
	 * Store input values
	 * When form fails to submit values can be reinserted into form
	 *
	 * @param $field
	 *
	 * @return bool
	 */
	public function input( $field ) {
		if( empty( $field ) ) {
			return false;
		}

		if( !empty( $_SESSION['form'] ) ) {
			$input = $_SESSION['form'];
			if( !empty( $input[$field] ) ) {
				return $input[$field];
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}