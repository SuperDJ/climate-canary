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

	if( !empty( $_GET['co2'] ) && !empty( $_GET['temp'] ) && !empty( $_GET['humidity'] ) ) {
		require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/core/engine.php';

		$db = new Database();
		$sensor = new Sensor( $db );

		// Clear variables of user input
		$humidity = $db->sanitize($_GET['humidity']);
		$humidityDate = $db->sanitize($_GET['humidityDate']);
		$co2 = $db->sanitize($_GET['co2']);
		$co2Date = $db->sanitize($_GET['co2Date']);
		$temp = $db->sanitize($_GET['temp']);
		$tempDate = $db->sanitize($_GET['tempDate']);

		if( $sensor->addData($humidity, $humidityDate, $co2, $co2Date, $temp, $tempDate) ) {
			echo 'toegevoegd';
		} else {
			echo 'nope';
		}
	}
} else {
	echo 'Page not posted';
}