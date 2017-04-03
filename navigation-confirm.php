<?php
$title = 'Navigeer naar bevestiging';
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/header.php';

$from = $db->sanitize( $_GET['from'] );
$fLat = $db->sanitize( $_GET['fromLat'] );
$fLng = $db->sanitize( $_GET['fromLng'] );
$to = $db->sanitize( $_GET['to'] );
$tLat = $db->sanitize( $_GET['toLat'] );
$tLng = $db->sanitize( $_GET['toLng'] );
if( !empty( $from ) && !empty( $fLat ) && !empty( $fLng ) && !empty( $to ) && !empty( $tLat ) && !empty( $tLng ) ) {
?>
	<section class="row">
		<div class="map-confirmation col col-xs-12" id="map"></div> <!-- Display Google maps -->
	</section>

	<section class="row details">
		<div class="col col-xs-8 col-sm-8">
			<div class="row">
				<div class="col col-xs-12">
					<p>Naar <?php echo $to; ?></p>
					<p>Vanaf <span class="colored"><?php echo $from; ?></span></p>
				</div>

				<div class="col col-xs-12">

				</div>
			</div>
		</div>

		<div class="col col-xs-4 col-sm-4">

		</div>
	</section>

	<script>
		function initMap() {
			var directionsService = new google.maps.DirectionsService;
			var directionsDisplay = new google.maps.DirectionsRenderer;
			var map = new google.maps.Map(document.getElementById('map'), {
				zoom: 7,
				center: {lat: <?php echo $fLat; ?>, lng: <?php echo $fLng; ?>}
			});
			directionsDisplay.setMap(map);

			//var onChangeHandler = function() {
				calculateAndDisplayRoute(directionsService, directionsDisplay);
		/*	};
			document.getElementById('start').addEventListener('change', onChangeHandler);
			document.getElementById('end').addEventListener('change', onChangeHandler);*/
		}

		function calculateAndDisplayRoute(directionsService, directionsDisplay) {
			directionsService.route({
				origin: {lat: <?php echo $fLat; ?>, lng: <?php echo $fLng; ?>} /*document.getElementById('start').value*/,
				destination: {lat: <?php echo $tLat; ?>, lng: <?php echo $tLng; ?>},
				travelMode: 'DRIVING'
			}, function(response, status) {
				if (status === 'OK') {
					directionsDisplay.setDirections(response);
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