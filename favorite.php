<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/core/engine.php';
if( !empty( $_GET['id'] ) && $db->exists('id', 'address', 'id', base64_decode( $_GET['id'] ) ) && !empty( $_GET['type'] ) ) {
	$id = (int)base64_decode( $_GET['id'] );
	$type = $_GET['type'];

	switch( $type ) {
		case 'add':
			$stmt = $db->mysqli->prepare("UPDATE `address` SET `favorite` = 1 WHERE `id` = :id");
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();

			if( $stmt->rowCount() >= 1 ) {
				$stmt = null;
				$user->to('navigate-to.php?message=Favoriet toegevoegd&type=add');
			} else {
				$stmt = null;
				$user->to('navigate-to.php?message=Favoriet niet toegevoegd&type=add');
			}
			break;
		case 'delete':
			$stmt = $db->mysqli->prepare("UPDATE `address` SET `favorite` = DEFAULT WHERE `id` = :id");
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();

			if( $stmt->rowCount() >= 1 ) {
				$stmt = null;
				$user->to('navigate-to.php?message=Favoriet verwijderd&type=delete');
			} else {
				$stmt = null;
				$user->to('navigate-to.php?message=Favoriet niet verwijderd&type=delete');
			}
			break;
	}
} else {
	$user->to('navigate-to.php');
}