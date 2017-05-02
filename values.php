<?php
if( empty( $_GET['type'] ) ) {
    $user->to('/climate-canary/');
} else {
    $type = $_GET['type'];

    switch($type) {
        case 'temperature':
            $title = 'Temperatuur';
            $table = 'degrees';
            break;
        case 'carbon-dioxide':
            $title = 'Co2 gehalte';
            $table = 'co';
            break;
        case 'humidity':
            $title =  'Luctvochtigheid';
            $table = $type;
            break;
    }
}
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/header.php';
$sensor = new Sensor($db);
?>

<section class="row">
    <div class="col-sm-12 return">
        <a href="<?php echo ( !empty( $previous ) ? $previous : '/climate-canary/' ); ?>" class="return"><i class="material-icons">arrow_back</i> Terug</a>
    </div>

    <div class="col-sm-12 content">
        <h1><?php echo $title; ?></h1>
        <p id="value"><?php echo $sensor->get($table); ?></p>
        <p>in de auto</p>
        <p id="alert"></p>
    </div>
</section>

<script>
    var $value = document.getElementById('value'),
        $alert = document.getElementById('alert'),
        $type = '<?php echo $table; ?>',
        $degrees = '<?php echo ( $session->exists('settings') ? $session->get('settings')['graden'] : 'Celsius' ); ?>';

    setInterval( function() {
		var http = new XMLHttpRequest(),
		    url = '/climate-canary/core/ajax.php?type='+$type;
		http.open( 'GET', url, true );

		//Send the proper header information along with the request
		http.setRequestHeader('Content-type', 'text/plain');

		http.onreadystatechange = function() {//Call a function when the state changes.
			if( http.readyState == 4 && http.status == 200 ) {
				var $response = Number(http.responseText);
				switch($type) {
                    case 'degrees':
						$response = $response.toFixed(1);
                    	if( $response < 17.5 ) {
                    		if( $degrees == 'Fahrenheit' ) {
                    			$response = ($response * 1.8) + 32;
                            }

                            $value.innerHTML = '<span class="sc-red-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
                            $alert.innerText = 'Zet de kachel wat hoger';
                        }

						if( $response > 17.5 && $response <18.5 ) {
							if( $degrees == 'Fahrenheit' ) {
								$response = ($response * 1.8) + 32;
							}

							$value.innerHTML = '<span class="sc-orange-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
							$alert.innerText = 'De temperatuur dreigt te laag te worden.';
						}

						if( $response > 18.5 && $response < 20.5 ) {
							if( $degrees == 'Fahrenheit' ) {
								$response = ($response * 1.8) + 32;
							}

							$value.innerHTML = '<span class="sc-teal-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
							$alert.innerText = 'De temperatuur is precies goed';
						}

						if( $response > 20.5 && $response <21.5 ) {
							if( $degrees == 'Fahrenheit' ) {
								$response = ($response * 1.8) + 32;
							}

							$value.innerHTML = '<span class="sc-orange-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
							$alert.innerText = 'De temperatuur dreigt te hoog te worden.';
						}

						if( $response > 21.5) {
							if( $degrees == 'Fahrenheit' ) {
								$response = ($response * 1.8) + 32;
							}

							$value.innerHTML = '<span class="sc-red-text">' + $response + ($degrees == 'Celsius' ? '&deg;C' : '&deg;F' ) +'</span>';
							$alert.innerText = 'Zet de kachel lager of doe een raam open';
						}
                    	break;
                    case 'co':
                    	if( $response > 1250 ) {
                    		$value.innerHTML = '<span class="sc-red-text">'+$response+'ppm</span>';
                    		$alert.innerText = 'Doe een raam open of zet de luchtoevoer op van buitenaf';
                        }

						if( $response > 1150 && $response < 1250 ) {
							$value.innerHTML = '<span class="sc-orange-text">'+$response+'ppm</span>';
							$alert.innerText = 'De Co2 waarde dreigt te hoog te worden.';
						}

                        if( $response < 1150 ) {
                    		$value.innerHTML = '<span class="sc-teal-text">'+$response+'ppm</span>';
                    		$alert.innerText = 'De Co2 waarde is goed';
                        }
                    	break;
                    case 'humidity':
                    	if( $response > 60 ) {
							$value.innerHTML = '<span class="sc-red-text">'+$response+'%</span>';
							$alert.innerText = 'Zet de kachel hoger';
                        }

						if( $response > 57 && $response < 60 ) {
							$value.innerHTML = '<span class="sc-orange-text">'+$response+'%</span>';
							$alert.innerText = 'De luchtvochtigheid dreigt te hoog te worden.';
						}

						if( $response < 40 ) {
							$value.innerHTML = '<span class="sc-red-text">'+$response+'%</span>';
							$alert.innerText = 'Zet een raam open of gebruik een luchtbevochtiger';
						}

						if( $response > 40 && $response < 43 ) {
							$value.innerHTML = '<span class="sc-orange-text">'+$response+'%</span>';
							$alert.innerText = 'De luchtvochtigheid dreigt te laag te worden.';
						}

						if( $response > 43 && $response < 57 ) {
							$value.innerHTML = '<span class="sc-teal-text">'+$response+'%</span>';
							$alert.innerText = 'De luchtvochtigheid is precies goed';
						}
                    	break;
                }
			}
		}
		http.send();
    }, 15000);
</script>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/footer.php';