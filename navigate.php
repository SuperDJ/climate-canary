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

	<script>
		var time = document.getElementById('time'),
			distance = document.getElementById('distance'),
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