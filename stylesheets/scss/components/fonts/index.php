<?php
$fontName = 'Montserrat';
$scan = scandir('./'.$fontName);

foreach( $scan as $key => $font ) {
	if( !in_array( $font, array( '.', '..', 'LICENSE_OFL.txt' ) ) ) {
		echo '@font-face {<br>font-family: "'.$fontName.'";<br>src: url(\'#{$'.strtolower($fontName).'-font-path}'.$font.'\');<br>';

		if( strpos( $font, 'Thin' ) !== false || strpos( $font, '100' ) !== false ) {
			echo 'font-weight: 100;<br>';
		} else if( strpos( $font, 'Light' ) !== false || strpos( $font, '300' ) !== false ) {
			echo 'font-weight: 300;<br>';
		} else if( strpos( $font, 'Medium' ) !== false || strpos( $font, '500' ) !== false ) {
			echo 'font-weight: 500;<br>';
		} else if( strpos( $font, '600' ) !== false ) {
			echo 'font-weight: 600;<br>';
		} else if( strpos( $font, 'Bold' ) !== false || strpos( $font, '700' ) !== false ) {
			echo 'font-weight: 700;<br>';
		} else if( strpos( $font, '800' ) !== false ) {
			echo 'font-weight: 800;<br>';
		} else if( strpos( $font, 'Black' ) !== false || strpos( $font, '900' ) !== false ) {
			echo 'font-weight: 900;<br>';
		} else {
			echo 'font-weight: 400;<br>';
		}

		if( strpos( $font, 'italic' ) !== false ) {
			echo 'font-style: italic;<br>';
		} else {
			echo 'font-style: normal;<br>';
		}
		echo'}<br><br>';
	}
}