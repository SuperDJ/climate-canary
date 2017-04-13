<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/core/engine.php';

$db = new Database();
$sensor = new Sensor($db);

function createDate( $date ) {
	$numbers = explode('-', $date);

	$i = 0;
	foreach( $numbers as $key => $value ) {
		if( $value < 10 && $value != '0') {
			$numbers[$i] = '0'.$value;
		}
		$i++;
	}

	return $numbers[2].'-'.$numbers[1].'-'.$numbers[0];
}

function createTime( $time ) {
	$numbers = explode(':', $time);

	$i = 0;
	foreach( $numbers as $key => $value ) {
		if( $value < 10 && $value != '00' && mb_strlen( $value ) < 2 ) {
			$numbers[$i] = '0'.$value;
		}
		$i++;
	}

	return $numbers[0].':'.$numbers[1].':'.$numbers[2];
}

/**************** Get humidity ************/
$humidity = './humidity.csv';
$hArray = array();
if( file_exists( $humidity ) ) {
	$file = fopen( $humidity, 'r' );
	$i = 0;
	while( ( $line = fgetcsv( $file, null, ';' ) ) !== false ) {
		//$line is an array of the csv elements
		//print_r( $line );
		if( $line[0] != 'Date/Time' && strpos( $line[1], '%') != true ) {
			$date = explode( ' ', $line[0] );
			$hArray[$i]['date'] = createDate( $date[0] ).' '.createTime( $date[1].':00' );
			$hArray[$i]['humidity'] = $line[1];
		}

		$i++;
	}
	fclose( $file );

	if( $sensor->add( $hArray, 'humidity' ) ) {
		echo 'humidity added';
	} else {
		echo 'humidity not added';
	}
} else {
	echo $humidity.' not found';
}
/**************** End humidity ************/

/**************** Get Co2 ************/
$co = './co2.csv';
$cArray = array();
if( file_exists( $co ) ) {
	$file = fopen( $co, 'r' );
	$i = 0;
	while( ( $line = fgetcsv( $file, null, ';' ) ) !== false ) {
		//$line is an array of the csv elements
		//print_r( $line );
		//echo $line[0];
		if( $line[0] != 'Date/Time' && !empty( $line[1] ) ) {
			$date = explode( ' ', $line[0] );
			$cArray[$i]['date'] = createDate( $date[0] ).' '.createTime( $date[1].':00' );
			$cArray[$i]['co'] = $line[1];
		}

		$i++;
	}

	fclose( $file );

	if( $sensor->add( $cArray, 'co' ) ) {
		echo 'co2 added';
	} else {
		echo 'co2 not added';
	}
} else {
	echo $co.' not found';
}
/**************** End Co2 ************/

/**************** Get degrees ************/
$degrees = './graden.csv';
$gArray = array();
if( file_exists( $degrees ) ) {
	$file = fopen( $degrees, 'r' );
	$i = 0;
	while( ( $line = fgetcsv( $file, null, ';' ) ) !== false ) {
		//$line is an array of the csv elements
		//print_r( $line );
		//echo $line[0];
		if( $line[0] != 'Date/Time' && $line[1] != 0 ) {
			$date = explode( ' ', $line[0] );
			$gArray[$i]['date'] = createDate( $date[0] ).' '.createTime( $date[1].':00' );
			$gArray[$i]['degrees'] = $line[1];
		}

		$i++;
	}
	fclose( $file );

	print_r($gArray);

	if( $sensor->add( $gArray, 'degrees' ) ) {
		echo 'degrees added';
	} else {
		echo 'degrees not added';
	}
} else {
	echo $degrees.' not found';
}
/**************** End degrees ************/