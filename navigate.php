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
    $sensor = new Sensor($db);
	?>
    <div id="pause">
        Neem om <span id="pauseTime"></span> een pauze
    </div>

    <section class="test">
        <a href="/climate-canary/navigate-to.php" class="sc-raised-button">Terug</a>
    </section>

	<section class="row">
		<div class="map-confirmation col col-xs-12" id="map"></div> <!-- Display Google maps -->
	</section>

    <div class="sidebar">
        <div id="speed">0<?php echo ( $session->exists('settings') ? $session->get('settings')['snelheid'] : 'KM/H' ); ?></div>

		<?php
        $response = $sensor->get('degrees');
        $degrees = ( $session->exists('settings') ? $session->get('settings')['graden'] : 'Celsius' );
        $html = '';

        if( $response < 17.5 ) {
            if( $degrees == 'Fahrenheit' ) {
                $response = ( $response * 1.8 ) + 32;
            }
            $response = number_format( $response, 1, '.', '');

            $html .= '<span class="sc-red-text">'.$response.($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ).'</span>';
        }

        if( $response > 17.5 && $response < 18.5 ) {
            if( $degrees == 'Fahrenheit' ) {
                $response = ( $response * 1.8 ) + 32;
            }
            $response = number_format( $response, 1, '.', '');

            $html .= '<span class="sc-orange-text">'.$response.($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ).'</span>';
        }

        if( $response > 18.5 && $response < 20.5 ) {
            if( $degrees == 'Fahrenheit' ) {
                $response = ( $response * 1.8 ) + 32;
            }
            $response = number_format( $response, 1, '.', '');

            $html .= '<span class="sc-teal-text">'.$response.($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ).'</span>';
        }

        if( $response > 20.5 && $response < 21.5 ) {
            if( $degrees == 'Fahrenheit' ) {
                $response = ( $response * 1.8 ) + 32;
            }
            $response = number_format( $response, 1, '.', '');

            $html .= '<span class="sc-orange-text">'.$response.($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ).'</span>';
        }

        if( $response > 21.5 ) {
            if( $degrees == 'Fahrenheit' ) {
                $response = ( $response * 1.8 ) + 32;
            }
            $response = number_format( $response, 1, '.', '');

            $html .= '<span class="sc-red-text">'.$response.($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ).'</span>';
        }
        ?>
        <a href="/climate-canary/values.php?type=temperature" id="degrees"><?php echo $html; ?></a>

		<?php
        $response = $sensor->get('co');
        $html = '';

        if( $response > 1250 ) {
            $html .= '<span class="sc-red-text">'.$response.'ppm</span>';
        }

        if( $response > 1150 && $response < 1250 ) {
            $html .= '<span class="sc-orange-text">'.$response.'ppm</span>';
        }

        if( $response < 1150 ) {
            $html .= '<span class="sc-teal-text">'.$response.'ppm</span>';
        }
        ?>
        <a href="/climate-canary/values.php?type=carbon-dioxide" id="co"><?php echo $html; ?></span></a>

		<?php
        $response = $sensor->get('humidity');
        $html = '';

        if( $response > 60 ) {
            $html .= '<span class="sc-red-text">'.$response.'%</span>';
        }

        if( $response > 57 && $response < 60 ) {
            $html .= '<span class="sc-orange-text">'.$response.'%</span>';
        }

        if( $response < 40 ) {
            $html .= '<span class="sc-red-text">'.$response.'%</span>';
        }

        if( $response > 40 && $response < 43 ) {
            $html .= '<span class="sc-orange-text">'.$response.'%</span>';
        }

        if( $response > 43 && $response < 57 ) {
            $html .= '<span class="sc-teal-text">'.$response.'%</span>';
        }
        ?>
        <a href="/climate-canary/values.php?type=humidity" id="humidity"><?php echo $html; ?></a>

        <div id="time"></div>

        <div id="arrival"></div>
    </div>

	<script>
		var time = document.getElementById('time'),
            arrival = document.getElementById('arrival'),
			distance = document.getElementById('distance'),
            degrees = document.getElementById('degrees'),
            pause = document.getElementById('pause'),
			$degrees = '<?php echo ( $session->exists('settings') ? $session->get('settings')['graden'] : 'Celsius' ); ?>',
            co = document.getElementById('co'),
            humidity = document.getElementById('humidity'),
			start = document.getElementById('start'),
            date = new Date(),
            duration = '<?php echo $_GET['time']; ?>',
            hours = 0,
            minutes = 0,
            endDate = new Date(date),
            pauseTime = document.getElementById('pauseTime'),
            $pauseTime = new Date(date),
            $notifications = '<?php echo ( $session->exists('settings') ? $session->get('settings')['notification-receive'] : 'yes' ); ?>',
            $notification = document.getElementById('notification');
            $notificationTitle = document.getElementById('notification-title'),
            $notificationContent = document.getElementById('notification-content');

        // Set current time
        time.innerText =
            ( date.getHours() < 10 ? '0' + date.getHours() : date.getHours() ) + ':' +
            ( date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes() );

        // Check if time has hours
        if( duration.indexOf('hours') > 0 || duration.indexOf('uur') > 0 ) {
			hours = duration.split(' ')[0];
            minutes = duration.split(' ')[2];
        } else {
			minutes = duration.split(' ')[0];
        }

        // Check if route needs breaks
        if( hours > 4 ) {
        	minutes = (hours / 2) * 15;
        	$pauseTime.setHours(date.getHours() + 2);
        	pauseTime.innerText =
                ( $pauseTime.getHours() < 10 ? '0' + $pauseTime.getHours() : $pauseTime.getHours() ) + ':' +
                ( $pauseTime.getMinutes() < 10 ? '0' + $pauseTime.getMinutes() : $pauseTime.getMinutes() );
            pause.style.display = 'block';
        } else {
            pause.remove();
        }

        // Get arrival time
		endDate.setHours( date.getHours() + Number( hours ) );
		endDate.setMinutes( date.getMinutes() + Number( minutes ) );
		console.log( endDate );
        arrival.innerText =
            ( endDate.getHours() < 10 ? '0' + endDate.getHours() : endDate.getHours() ) + ':' +
            ( endDate.getMinutes() < 10 ? '0' + endDate.getMinutes() : endDate.getMinutes() ) + ' aankomst';

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
					if( $response < 17.5 ) {
						if( $degrees == 'Fahrenheit' ) {
							$response = ($response * 1.8) + 32;
						}
						$response = $response.toFixed(1);

						degrees.innerHTML = '<span class="sc-red-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
						$notificationTitle.innerHTML = '<span class="sc-red-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
						$notificationContent.innerText = 'Zet de kachel wat hoger';
						if( $notifications == 'yes' ) {
						    $notification.className += ' show';

						    setTimeout(function() {
						        $notification.className = $notification.className.replace(' show', '');
                            }, 2500);
                        }
					}

					if( $response > 17.5 && $response < 18.5 ) {
						if( $degrees == 'Fahrenheit' ) {
							$response = ($response * 1.8) + 32;
						}
						$response = $response.toFixed(1);

						degrees.innerHTML = '<span class="sc-orange-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
						$notificationTitle.innerHTML = '<span class="sc-orange-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
						$notificationContent.innerText = 'De temperatuur dreigt te laag te worden.';
						if( $notifications == 'yes' ) {
							$notification.className += ' show';

							setTimeout(function() {
							    $notification.className = $notification.className.replace(' show', '');
                            }, 2500);
						}
					}

					if( $response > 18.5 && $response < 20.5 ) {
						if( $degrees == 'Fahrenheit' ) {
							$response = ($response * 1.8) + 32;
						}
						$response = $response.toFixed(1);

						degrees.innerHTML = '<span class="sc-teal-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
						$notificationTitle.innerHTML = '<span class="sc-teal-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
						$notificationContent.innerText = 'De temperatuur is precies goed';
						if( $notifications == 'yes' ) {
							$notification.className += ' show';

							setTimeout(function() {
							    $notification.className = $notification.className.replace(' show', '');
                            }, 2500);
						}
					}

					if( $response > 20.5 && $response < 21.5 ) {
						if( $degrees == 'Fahrenheit' ) {
							$response = ($response * 1.8) + 32;
						}
						$response = $response.toFixed(1);

						degrees.innerHTML = '<span class="sc-orange-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
						$notificationTitle.innerHTML = '<span class="sc-orange-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
						$notificationContent.innerText = 'De temperatuur dreigt te hoog te worden.';
						if( $notifications == 'yes' ) {
							$notification.className += ' show';

							setTimeout(function() {
							    $notification.className = $notification.className.replace(' show', '');
                            }, 2500);
						}
					}

					if( $response > 21.5) {
						if( $degrees == 'Fahrenheit' ) {
							$response = ($response * 1.8) + 32;
						}
						$response = $response.toFixed(1);

						degrees.innerHTML = '<span class="sc-red-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
						$notificationTitle.innerHTML = '<span class="sc-red-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
						$notificationContent.innerText = 'Zet de kachel lager of doe een raam open';
						if( $notifications == 'yes' ) {
							$notification.className += ' show';

							setTimeout(function() {
							    $notification.className = $notification.className.replace(' show', '');
                            }, 2500);
						}
					}
				}
			}
			http.send();
		}, 15000);
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
						$notificationTitle.innerHTML = '<span class="sc-red-text">'+$response+'%</span>';
						$notificationContent.innerText = 'Zet de kachel hoger';
						if( $notifications == 'yes' ) {
							$notification.className += ' show';

							setTimeout(function() {
							    $notification.className = $notification.className.replace(' show', '');
                            }, 2500);
						}
					}

					if( $response > 57 && $response < 60 ) {
						humidity.innerHTML = '<span class="sc-orange-text">'+$response+'%</span>';
						$notificationTitle.innerHTML = '<span class="sc-orange-text">'+$response+'%</span>';
						$notificationContent.innerText = 'De luchtvochtigheid dreigt te hoog te worden.';
						if( $notifications == 'yes' ) {
							$notification.className += ' show';

							setTimeout(function() {
							    $notification.className = $notification.className.replace(' show', '');
                            }, 2500);
						}
					}

					if( $response < 40 ) {
						humidity.innerHTML = '<span class="sc-red-text">'+$response+'%</span>';
						$notificationTitle.innerHTML = '<span class="sc-red-text">'+$response+'%</span>';
						$notificationContent.innerText = 'Zet een raam open of gebruik een luchtbevochtiger';
						if( $notifications == 'yes' ) {
							$notification.className += ' show';

							setTimeout(function() {
							    $notification.className = $notification.className.replace(' show', '');
                            }, 2500);
						}
					}

					if( $response > 40 && $response < 43 ) {
						humidity.innerHTML = '<span class="sc-orange-text">'+$response+'%</span>';
						$notificationTitle.innerHTML = '<span class="sc-orange-text">'+$response+'%</span>';
						$notificationContent.innerText = 'De luchtvochtigheid dreigt te laag te worden.';
						if( $notifications == 'yes' ) {
							$notification.className += ' show';

							setTimeout(function() {
							    $notification.className = $notification.className.replace(' show', '');
                            }, 2500);
						}
					}

					if( $response > 43 && $response < 57 ) {
						humidity.innerHTML = '<span class="sc-teal-text">'+$response+'%</span>';
						$notificationTitle.innerHTML = '<span class="sc-teal-text">'+$response+'%</span>';
						$notificationContent.innerText = 'De luchtvochtigheid is precies goed';
						if( $notifications == 'yes' ) {
							$notification.className += ' show';

							setTimeout(function() {
							    $notification.className = $notification.className.replace(' show', '');
                            }, 2500);
						}
					}
				}
			}
			http.send();
		}, 14000);
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
					if( $response > 1250 ) {
						co.innerHTML = '<span class="sc-red-text">'+$response+'ppm</span>';
						$notificationTitle.innerHTML = '<span class="sc-red-text">'+$response+'ppm</span>';
						$notificationContent.innerText = 'Doe een raam open of zet de luchtoevoer op van buitenaf';
						if( $notifications == 'yes' ) {
							$notification.className += ' show';

							setTimeout(function() {
							    $notification.className = $notification.className.replace(' show', '');
                            }, 2500);
						}
					}

					if( $response > 1150 && $response < 1250 ) {
						co.innerHTML = '<span class="sc-orange-text">'+$response+'ppm</span>';
						$notificationTitle.innerHTML = '<span class="sc-orange-text">'+$response+'ppm</span>';
						$notificationContent.innerText = 'De Co2 waarde dreigt te hoog te worden.';
						if( $notifications == 'yes' ) {
							$notification.className += ' show';

							setTimeout(function() {
							    $notification.className = $notification.className.replace(' show', '');
                            }, 2500);
						}
					}

					if( $response < 1150 ) {
						co.innerHTML = '<span class="sc-teal-text">'+$response+'ppm</span>';
						$notificationTitle.innerHTML = '<span class="sc-teal-text">'+$response+'ppm</span>';
						$notificationContent.innerText = 'De Co2 waarde is goed';
						if( $notifications == 'yes' ) {
							$notification.className += ' show';

                            setTimeout(function() {
                                $notification.className = $notification.className.replace(' show', '');
                            }, 2500);
						}
					}
				}
			}
			http.send();
		}, 16000);
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