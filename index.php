<?php
$title = 'Home';
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/header.php';

$sensor = new Sensor( $db );
?>

<section class="row">
    <div class="col col-xs-6 col-sm-6">
        <a href="/climate-canary/<?php echo $address->getHomeUrl(); ?>" class="sc-card sc-center home address">
            <div class="sc-card-supporting">
                <i class="material-icons">home</i>
                <span class="text">Naar huis</span>
            </div>
        </a>
    </div>


    <div class="col col-xs-6 col-sm-6">
        <a href="/climate-canary/navigate-to.php" class="sc-card sc-center home">
            <div class="sc-card-supporting">
                <i class="material-icons">navigation</i>
                <span class="text">Navigeer naar</span>
            </div>
        </a>
    </div>
</section>

<section class="row">
    <div class="col col-xs-6 col-sm-6">
        <a href="/climate-canary/values.php?type=temperature" class="sc-card sc-center home data">
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
            <span class="data" id="degrees"><?php echo $html; ?></span><br>
            <span class="text">Temperatuur</span>
        </a>
    </div>

    <div class="col col-xs-6 col-sm-6">
        <a href="/climate-canary/values.php?type=carbon-dioxide" class="sc-card sc-center home data">
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
            <span class="data" id="co"><?php echo $html; ?></span><br>
            <span class="text">CO<sub>2</sub> gehalte</span>
        </a>
    </div>
</section>

<section class="row">
    <div class="col col-xs-6 col-sm-6">
        <a href="/climate-canary/values.php?type=humidity" class="sc-card sc-center home data">
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
            <span class="data" id="humidity"><?php echo $html; ?></span><br>
            <span class="text">Luchtvochigheid</span>
        </a>
    </div>

    <div class="col col-xs-6 col-sm-6">
        <a href="/climate-canary/settings.php" class="sc-card sc-center home">
            <div class="sc-card-supporting">
                <i class="material-icons">settings</i>
                <span class="text">Instellingen</span>
            </div>
        </a>
    </div>
</section>
<script>
    var degrees = document.getElementById('degrees'),
        $degrees = '<?php echo ( $session->exists('settings') ? $session->get('settings')['graden'] : 'Celsius' ); ?>',
        co = document.getElementById('co'),
        humidity = document.getElementById('humidity');

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
				}

				if( $response > 17.5 && $response <18.5 ) {
					if( $degrees == 'Fahrenheit' ) {
						$response = ($response * 1.8) + 32;
					}
					$response = $response.toFixed(1);

					degrees.innerHTML = '<span class="sc-orange-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
				}

				if( $response > 18.5 && $response < 20.5 ) {
					if( $degrees == 'Fahrenheit' ) {
						$response = ($response * 1.8) + 32;
					}
					$response = $response.toFixed(1);

					degrees.innerHTML = '<span class="sc-teal-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
				}

				if( $response > 20.5 && $response < 21.5 ) {
					if( $degrees == 'Fahrenheit' ) {
						$response = ($response * 1.8) + 32;
					}
					$response = $response.toFixed(1);

					degrees.innerHTML = '<span class="sc-orange-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
				}

				if( $response > 21.5) {
					if( $degrees == 'Fahrenheit' ) {
						$response = ($response * 1.8) + 32;
					}
					$response = $response.toFixed(1);

					degrees.innerHTML = '<span class="sc-red-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
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
				}

				if( $response > 57 && $response < 60 ) {
					humidity.innerHTML = '<span class="sc-orange-text">'+$response+'%</span>';
				}

				if( $response < 40 ) {
					humidity.innerHTML = '<span class="sc-red-text">'+$response+'%</span>';
				}

				if( $response > 40 && $response < 43 ) {
					humidity.innerHTML = '<span class="sc-orange-text">'+$response+'%</span>';
				}

				if( $response > 43 && $response < 57 ) {
					humidity.innerHTML = '<span class="sc-teal-text">'+$response+'%</span>';
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
				}

				if( $response > 1150 && $response < 1250 ) {
					co.innerHTML = '<span class="sc-orange-text">'+$response+'ppm</span>';
				}

				if( $response < 1150 ) {
					co.innerHTML = '<span class="sc-teal-text">'+$response+'ppm</span>';
				}
            }
        }
        http.send();
    }, 16000);
    /** End Co **/
</script>

<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/footer.php';