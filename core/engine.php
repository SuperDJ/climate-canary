<?php
session_start(); // Start session

// Set charset
header( 'Content-Type: text/html; charset=UTF-8' );

// Set mb charset
mb_internal_encoding( 'UTF-8' );

// Set time zone
$date = new DateTime( null, new DateTimeZone( 'Europe/Amsterdam' ) );

// Set global variables
$root = $_SERVER['DOCUMENT_ROOT'].'/';
set_include_path( $root );
require_once 'core/defines.php';

// Global functions
require_once 'core/functions.php';

if( TEST === true ) {
	// Force PHP to show errors
	error_reporting( E_ALL );
	ini_set( 'display_errors', '1' );
} else {
	// Turn off all errors
	error_reporting(0);
}

// Individually load classes
spl_autoload_register(function( $class ) {
	require_once 'classes/'.$class.'.php';
});

$db = new Database();
$user = new User();
$session = new Session();
$cookie = new Cookie();