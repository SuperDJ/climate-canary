<?php
$file = str_replace( '.php', '', $_SERVER['PHP_SELF'] );
?>
<!DOCTYPE html>
<html lang="NL">
<head>
	<title><?php echo $title; ?> | ClimateCanary</title>

	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="/climate-canary/stylesheets/stylesheet.css">

	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="language" content="NL">
	<meta name="author" content="Mireille Heemstra, Sanne Nijboer, Maaike Pastoor, Vyrin Rambaran, Derkjan Super">

	<meta property="og:type" content="article">
	<meta property="og:title" content="<?php echo $title; ?> | ClimateCanary">
	<meta property="og:site_name" content="ClimateCanary">
	<meta property="article:section" content="<?php echo $title; ?>">

	<meta name="theme-color" content="#FFEB3B">
	<meta name="msapplication-navbutton-color" content="#FFEB3B">
	<meta name="apple-mobile-web-app-status-bar-style" content="#FFEB3B">
</head>

<body>

<header class="sc-appbar">
    <img src="/climate-canary/images/crazycanary_logo_white.png" alt="ClimateCanary logo">
</header>

<main>