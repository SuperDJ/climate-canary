<?php
class User extends Database {
	public $data = array(); // Store logged in user data so it isn't queried each time

	private $_id; // Store user id

	/**
	 * Go to url
	 *
	 * @param  string $url The url to go to
	 */
	public function to( $url ) {
		if( headers_sent() ) {
			// For JavaScript and when JavaScript is turned off
			echo '	<script>window.location = "'.$url.'";</script>
					<noscript><meta http-equiv="refresh" content="0;url='.$url.'"></noscript>';
		} else {
			header( 'Location: '.$url );
			exit();
		}
	}
}