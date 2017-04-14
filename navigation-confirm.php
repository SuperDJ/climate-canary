<?php
header('Access-Control-Allow-Origin: //maps.googleapis.com/');
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
		<div class="col col-xs-7">
            <div class="row">
                <span class="col col-xs-12">Naar <?php echo $to; ?></span>
                <span class="col col-xs-12">Vanaf <span class="colored"><?php echo $from; ?></span></span>
            </div>

            <div class="row">
                <span class="colored col col-xs-12" id="time"></span>
                <span class="colored col col-xs-12" id="distance"></span>
            </div>
        </div>

		<div class="col col-xs-5">
            <div class="col col-xs-12">
                <a href="/climate-canary/navigate-to.php" class="sc-raised-button grey">
                    <!--<i class="material-icons">arrow_back</i>--> Terug
                </a>
            </div>

            <div class="col col-xs-12">
                <a href="/climate-canary/navigate.php?from=<?php echo $from.'&fromLat='.$fLat.'&fromLng='.$fLng.'&to='.$to.'&toLat='.$tLat.'&toLng='.$tLng.'&distance=km&time=hours'; ?>" class="sc-raised-button" id="start">
                    <!--<i class="material-icons">navigation</i>-->Start
                </a>
            </div>
		</div>
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

            calculateAndDisplayRoute(directionsService, directionsDisplay);
		}

		function calculateAndDisplayRoute(directionsService, directionsDisplay) {
			directionsService.route({
				origin: {lat: <?php echo $fLat; ?>, lng: <?php echo $fLng; ?>} /*document.getElementById('start').value*/,
				destination: {lat: <?php echo $tLat; ?>, lng: <?php echo $tLng; ?>},
				travelMode: 'DRIVING'
			}, function(response, status) {
				if (status === 'OK') {
					var data = response.routes[0].legs[0];

					time.innerText = data.duration.text;
					distance.innerText = data.distance.text;

					// Replace km and hour from link to actual values
					var link = start.getAttribute('href'),
                        newLink = link.replace('km', data.distance.text),
                        newLink = newLink.replace('hours', data.duration.text);

					start.setAttribute('href', newLink);

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