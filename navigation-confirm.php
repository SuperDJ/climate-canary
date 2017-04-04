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
		<div class="col col-xs-7">
			<div class="row">
				<div class="col col-xs-12">
					<p>Naar <?php echo $to; ?></p>
					<p>Vanaf <span class="colored"><?php echo $from; ?></span></p>
				</div>

				<div class="col col-xs-12">

				</div>
			</div>
		</div>

		<div class="col col-xs-5">
            <div class="col col-xs-12">
                <a href="/climate-canary/navigate-to.php" class="sc-raised-button grey"><!--<i class="material-icons">arrow_back</i>--> Terug</a>
            </div>

            <div class="col col-xs-12">
                <a href="/climate-canary/navigate.php" class="sc-raised-button"><!--<i class="material-icons">navigation</i>-->Start</a>
            </div>
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

            calculateAndDisplayRoute(directionsService, directionsDisplay);
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

		// Create the XHR object.
		function createCORSRequest( method, url ) {
			var xhr = new XMLHttpRequest();
			if( 'withCredentials' in xhr ) {
				// XHR for Chrome/Firefox/Opera/Safari.
				xhr.open( method, url, true );
			} else if( typeof XDomainRequest != "undefined" ) {
				// XDomainRequest for IE.
				xhr = new XDomainRequest();
				xhr.open(method, url);
			} else {
				// CORS not supported.
				xhr = null;
			}
			return xhr;
		}

		function getRouteDetails( fLat, fLng, tLat, tLng ) {
			var url = 'https://maps.googleapis.com/maps/api/directions/json?origin='+fLat+','+fLng+'&destination='+tLat+','+tLng+'&key=AIzaSyAB9kYP7clJyhX45mt6y3LobeKA9L6ivNo',
			    xhr = createCORSRequest('GET', url);

			if( !xhr ) {
				console.log('CORS not supported');
				return
            }

            xhr.onload = function() {
				var data = xhr.responseText;
				console.log(data);
            }

            xhr.onerror = function() {
				console.log('There was an error making the request');
            }

            xhr.send();
        }
        getRouteDetails(<?php echo $fLat.', '.$fLng.', '.$tLat.', '.$tLng; ?>);
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