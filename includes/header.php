<?php
// Load file that runs everything
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/core/engine.php';

$previous = $session->get('previous');
$session->set('previous', $_SERVER['PHP_SELF']);

$file = str_replace( '.php', '', $_SERVER['PHP_SELF'] );
$file = str_replace( ' ', '-', $file);
$file = str_replace('/climate-canary/', '', $file);
?>
<!DOCTYPE html>
<html lang="NL">
<head>
	<title><?php echo $title; ?> | ClimateCanary</title>

	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="/climate-canary/stylesheets/stylesheet.css">
	<link rel="stylesheet" href="/climate-canary/stylesheets/bootstrap.min.css">

	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="language" content="NL">
	<meta name="author" content="Mireille Heemstra, Sanne Nijboer, Maaike Pastoor, Vyrin Rambaran, Derkjan Super">

	<meta property="og:type" content="article">
	<meta property="og:title" content="<?php echo $title; ?> | ClimateCanary">
	<meta property="og:site_name" content="ClimateCanary">
	<meta property="article:section" content="<?php echo $title; ?>">

	<meta name="theme-color" content="#FFCC33">
	<meta name="msapplication-navbutton-color" content="#FFCC33">
	<meta name="apple-mobile-web-app-status-bar-style" content="#FFCC33">
</head>

<body class="<?php echo $file; ?>">

<header class="sc-appbar">
    <a href="/climate-canary/">
        <img src="/climate-canary/images/crazycanary_logo_white.png" alt="ClimateCanary logo">
    </a>
</header>

<main class="container">