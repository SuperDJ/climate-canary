<?php
// Load file that runs everything
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/core/engine.php';

$previous = $session->get('previous');
$session->set('previous', $_SERVER['REQUEST_URI']);

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

    <!-- Make sure JS is enabled -->
    <noscript>
        <div class="sc-dialog sc-expanded">
            <div class="sc-dialog-container">
                <div class="sc-dialog-title">JavaScript aanzetten</div>
                U heeft JavaScript uit, zet JavaScript aan om de app te gebruiken.
            </div>
        </div>
    </noscript>

    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9">
    <!--[if IE ]>
    <div class="sc-dialog sc-expanded">
        <div class="sc-dialog-container">
            <div class="sc-dialog-title">Verouderde browser</div>
            U maakt gebruik van een verouderde en <u>onveilige</u> browser.
            Stap over op Chrome of Firefox.
        </div>
    </div>
    <![endif]-->
</head>

<body class="<?php echo $file; ?>">

<header class="sc-appbar">
    <a href="/climate-canary/">
        <img src="/climate-canary/images/crazycanary_logo_white.png" alt="ClimateCanary logo">
    </a>
</header>

<main class="container">