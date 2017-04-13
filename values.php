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
        $type = '<?php echo $table; ?>';

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
                    	if( $response < 18 ) {
                            $value.innerHTML = '<span class="sc-light-blue-text">'+$response+'&deg;C</span>';
                            $alert.innerText = 'Zet de kachel wat hoger';
                        }


						if( $response > 20 ) {
							$value.innerHTML = '<span class="sc-red-text">'+$response+'&deg;C</span>';
							$alert.innerText = 'Zet de kachel lager of doe een raam open';
						}


						if( $response > 18 && $response < 20 ) {
							$value.innerHTML = '<span class="sc-teal-text">'+$response+'&deg;C</span>';
							$alert.innerText = 'De temperatuur is precies goed';
						}
                    	break;
                    case 'co':
                    	if( $response > 1200 ) {
                    		$value.innerHTML = '<span class="sc-red-text">'+$response+'ppm</span>';
                    		$alert.innerText = 'Doe een raam open of zet de luchtoevoer op van buitenaf';
                        }

                        if( $response < 1200 ) {
                    		$value.innerHTML = '<span class="sc-teal-text">'+$response+'ppm</span>';
                    		$alert.innerText = 'De Co2 waarde is goed';
                        }
                    	break;
                    case 'humidity':
                    	if( $response > 60 ) {
							$value.innerHTML = '<span class="sc-red-text">'+$response+'%</span>';
							$alert.innerText = 'Zet de kachel hoger';
                        }

						if( $response < 40 ) {
							$value.innerHTML = '<span class="sc-light-blue-text">'+$response+'%</span>';
							$alert.innerText = 'Zet een raam open of gebruik een luchtbevochtiger';
						}

						if( $response > 40 && $response < 60 ) {
							$value.innerHTML = '<span class="sc-teal-text">'+$response+'%</span>';
							$alert.innerText = 'De luchtvochtigheid is precies goed';
						}
                    	break;
                }
			}
		}
		http.send();
    }, 1000);
</script>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/climate-canary/includes/footer.php';