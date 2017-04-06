<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/core/engine.php';
if( !empty( $_GET['id'] ) && $db->exists('id', 'address', 'id', base64_decode( $_GET['id'] )) ) {
	$id = $db->sanitize( base64_decode( $_GET['id'] ) );

	if( $address->delete($id) ) {
		$user->to('/climate-canary/navigate-to.php');
	} else {
		$user->to('/climate-canary/navigate-to.php');
	}
} else {
	$user->to('/climate-canary/navigate-to.php');
}