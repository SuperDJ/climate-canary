<?php
header('Access-Control-Allow-Origin: //maps.googleapis.com/');
$title = 'Navigeren';
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/header.php';

$from = $db->sanitize( $_GET['from'] );
$fLat = $db->sanitize( $_GET['fromLat'] );
$fLng = $db->sanitize( $_GET['fromLng'] );
$to = $db->sanitize( $_GET['to'] );
$tLat = $db->sanitize( $_GET['toLat'] );
$tLng = $db->sanitize( $_GET['toLng'] );
$distance = $db->sanitize($_GET['distance']);
$time = $db->sanitize($_GET['time']);

if( !empty( $from ) && !empty( $fLat ) && !empty( $fLng ) && !empty( $to ) && !empty( $tLat ) && !empty( $tLng ) && !empty( $distance ) && !empty( $time ) ) {
	?>
	<section class="row">
		<div class="map-confirmation col col-xs-12" id="map"></div> <!-- Display Google maps -->
	</section>

    <div class="sidebar">
        <div id="speed">0<?php echo ( $session->exists('settings') ? $session->get('settings')['snelheid'] : 'KM/H' ); ?></div>

        <div id="degrees">20</div>

        <div id="co">2000ppm</div>

        <div id="humidity">23%</div>

        <div id="time"></div>

        <div id="arrival"></div>
    </div>

	<script>
		var time = document.getElementById('time'),
			distance = document.getElementById('distance'),
            degrees = document.getElementById('degrees'),
			$degrees = '<?php echo ( $session->exists('settings') ? $session->get('settings')['graden'] : 'Celsius' ); ?>',
            co = document.getElementById('co'),
            humidity = document.getElementById('humidity'),
			start = document.getElementById('start');

		function initMap() {
			var directionsService = new google.maps.DirectionsService;
			var directionsDisplay = new google.maps.DirectionsRenderer;
			var map = new google.maps.Map(document.getElementById('map'), {
				zoom: 7,
				center: {lat: <?php echo $fLat; ?>, lng: <?php echo $fLng; ?>}
			});
			directionsDisplay.setMap(map);

			calculateAndDisplayRoute(directionsService, directionsDisplay, map);
		}

		function calculateAndDisplayRoute(directionsService, directionsDisplay, map) {
			directionsService.route({
				origin: {lat: <?php echo $fLat; ?>, lng: <?php echo $fLng; ?>} /*document.getElementById('start').value*/,
				destination: {lat: <?php echo $tLat; ?>, lng: <?php echo $tLng; ?>},
				travelMode: 'DRIVING',
			}, function(response, status) {
				if (status === 'OK') {
					var marker = new google.maps.Marker({
						position: {lat: <?php echo $fLat; ?>, lng: <?php echo $fLng; ?>},
						map: map,
						title: 'Current position',
						icon: {
							path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW,
							scale: 10,
							fillColor: '#FFCC33',
							fillOpacity: 1,
							strokeColor: '#FFCC33'
						}
					});
					marker.setMap(map);
					directionsDisplay.setOptions({preserveViewport:true});
					directionsDisplay.setDirections(response);
					map.setZoom(15);
				} else {
					window.alert('Directions request failed due to ' + status);
				}
			});
		}

		/** Degrees **/
		setInterval( function() {
			var http = new XMLHttpRequest(),
				url = '/climate-canary/core/ajax.php?type=degrees';
			http.open( 'GET', url, true );

			//Send the proper header information along with the request
			http.setRequestHeader('Content-type', 'text/plain');

			http.onreadystatechange = function() {//Call a function when the state changes.
				if( http.readyState == 4 && http.status == 200 ) {
					var $response = Number(http.responseText);
                    if( $response < 18 ) {
                        if( $degrees == 'Fahrenheit' ) {
                            $response = ($response * 1.8) + 32;
                        }

                        degrees.innerHTML = '<span class="sc-light-blue-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
                    }

                    if( $response > 20 ) {
                        if( $degrees == 'Fahrenheit' ) {
                            $response = ($response * 1.8) + 32;
                        }

                        degrees.innerHTML = '<span class="sc-red-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
                    }

                    if( $response > 18 && $response < 20 ) {
                        if( $degrees == 'Fahrenheit' ) {
                            $response = ($response * 1.8) + 32;
                        }

                        degrees.innerHTML = '<span class="sc-teal-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
                    }
				}
			}
			http.send();
		}, 1000);
		/** End Degrees **/

		/** Humidity **/
		setInterval( function() {
			var http = new XMLHttpRequest(),
				url = '/climate-canary/core/ajax.php?type=humidity'
			http.open( 'GET', url, true );

			//Send the proper header information along with the request
			http.setRequestHeader('Content-type', 'text/plain');

			http.onreadystatechange = function() {//Call a function when the state changes.
				if( http.readyState == 4 && http.status == 200 ) {
					var $response = Number(http.responseText);
                    if( $response > 60 ) {
                        humidity.innerHTML = '<span class="sc-red-text">'+$response+'%</span>';
                    }

                    if( $response < 40 ) {
                        humidity.innerHTML = '<span class="sc-light-blue-text">'+$response+'%</span>';
                    }

                    if( $response > 40 && $response < 60 ) {
                        humidity.innerHTML = '<span class="sc-teal-text">'+$response+'%</span>';
                    }
				}
			}
			http.send();
		}, 1001);
		/** End Humidity **/

		/** Co **/
		setInterval( function() {
			var http = new XMLHttpRequest(),
				url = '/climate-canary/core/ajax.php?type=co';
			http.open( 'GET', url, true );

			//Send the proper header information along with the request
			http.setRequestHeader('Content-type', 'text/plain');

			http.onreadystatechange = function() {//Call a function when the state changes.
				if( http.readyState == 4 && http.status == 200 ) {
					var $response = Number(http.responseText);
                    if( $response > 1200 ) {
                        co.innerHTML = '<span class="sc-red-text">'+$response+'ppm</span>';
                    }

                    if( $response < 1200 ) {
                        co.innerHTML = '<span class="sc-teal-text">'+$response+'ppm</span>';
                    }
				}
			}
			http.send();
		}, 1004);
		/** End Co **/
	</script>
	<script async defer
			src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAB9kYP7clJyhX45mt6y3LobeKA9L6ivNo&callback=initMap">
	</script>
	<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/footer.php';
} else {
	//Return user
	$user->to('navigate-to.php');
}