<?php
if( $_GET ) {
	if( !empty( $_GET['type'] ) ) {
		error_reporting( E_ALL );
		ini_set( 'display_errors', '1' );
		require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/core/engine.php';
		header('Content-Type: text/plain; charset=UTF-8');

		$db = new Database();
		$sensor = new Sensor( $db );

		$type = $db->sanitize($_GET['type']);
		echo $sensor->get($type);
	} else {
		echo 'No type posted';
	}
} else {
	echo 'Page not posted';
}